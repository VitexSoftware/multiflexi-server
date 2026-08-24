<?php

declare(strict_types=1);

/**
 * This file is part of the MultiFlexi package
 *
 * https://multiflexi.eu/
 *
 * (c) Vítězslav Dvořák <http://vitexsoftware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MultiFlexi\Api\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Concrete Job API implementation.
 *
 * Reads job data and output lines directly via PDO. Output is served from
 * job_output_lines; the jobs table no longer contains stdout/stderr columns.
 * Deletion is delegated to MultiFlexi\Job::deleteFromSQL() (vitexsoftware/
 * multiflexi-core) so runtemplate counters and related schedule rows stay
 * consistent, rather than reimplementing that logic here.
 */
class JobApi extends AbstractJobApi
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * GET /job/{jobId}.{suffix}  — Return a single job with its output lines.
     */
    public function getjobById(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $jobId,
        string $suffix
    ): ResponseInterface {
        $job = $this->fetchJob($jobId);

        if ($job === null) {
            return $response->withStatus(404);
        }

        $queryParams = $request->getQueryParams();
        $includeOutput = filter_var($queryParams['output'] ?? 'true', \FILTER_VALIDATE_BOOLEAN);

        if ($includeOutput) {
            $job['stdout'] = $this->assembleOutput($jobId, 'stdout');
            $job['stderr'] = $this->assembleOutput($jobId, 'stderr');
            $job['output_lines'] = $this->fetchOutputLines($jobId);
        }

        $response->getBody()->write((string) json_encode($job));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /jobs.{suffix}  — List jobs (without output for performance).
     */
    public function listjobs(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $suffix
    ): ResponseInterface {
        $queryParams = $request->getQueryParams();
        $limit = isset($queryParams['limit']) ? (int) $queryParams['limit'] : 100;
        $offset = isset($queryParams['offset']) ? (int) $queryParams['offset'] : 0;
        $order = strtoupper($queryParams['order'] ?? 'D') === 'A' ? 'ASC' : 'DESC';

        $limit = max(1, min($limit, 1000));
        $offset = max(0, $offset);

        $stmt = $this->pdo->prepare(<<<EOD
SELECT id, app_id, company_id, runtemplate_id, executor, exitcode,
                    begin, end, schedule, schedule_type, launched_by, app_version,
                    pid, task_id
             FROM job
             ORDER BY id {$order}
             LIMIT :limit OFFSET :offset
EOD, );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $jobs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $response->getBody()->write((string) json_encode($jobs));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * DELETE /job/{jobId}.{suffix}  — Delete a single job. Requires the admin RBAC role.
     */
    public function deletejobById(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $jobId,
        string $suffix
    ): ResponseInterface {
        if (!$this->currentUserHasAdminRole($request)) {
            return $response->withStatus(403);
        }

        if ($this->fetchJob($jobId) === null) {
            return $response->withStatus(404);
        }

        (new \MultiFlexi\Job($jobId))->deleteFromSQL($jobId);

        return $response->withStatus(200);
    }

    /**
     * DELETE /jobs.{suffix}  — Bulk delete jobs matching runtemplate_id / from / to.
     * Requires the admin RBAC role. At least one filter must be given.
     */
    public function deletejobs(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $suffix
    ): ResponseInterface {
        if (!$this->currentUserHasAdminRole($request)) {
            return $response->withStatus(403);
        }

        $queryParams = $request->getQueryParams();
        $runtemplateId = $queryParams['runtemplate_id'] ?? null;
        $from = $queryParams['from'] ?? null;
        $to = $queryParams['to'] ?? null;
        $dryRun = filter_var($queryParams['dry_run'] ?? 'false', \FILTER_VALIDATE_BOOLEAN);

        if ($runtemplateId === null && $from === null && $to === null) {
            $response->getBody()->write((string) json_encode(['error' => 'At least one of runtemplate_id, from, to is required']));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $conditions = [];
        $params = [];

        if ($runtemplateId !== null) {
            $conditions[] = 'runtemplate_id = :runtemplate_id';
            $params[':runtemplate_id'] = (int) $runtemplateId;
        }

        if ($from !== null) {
            $conditions[] = 'begin >= :from';
            $params[':from'] = $from;
        }

        if ($to !== null) {
            $conditions[] = 'begin <= :to';
            $params[':to'] = $to;
        }

        $stmt = $this->pdo->prepare('SELECT id FROM job WHERE '.implode(' AND ', $conditions));
        $stmt->execute($params);
        $jobIds = array_map('intval', array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'id'));

        if (!$dryRun) {
            foreach ($jobIds as $jobId) {
                (new \MultiFlexi\Job($jobId))->deleteFromSQL($jobId);
            }
        }

        $response->getBody()->write((string) json_encode([
            'dry_run' => $dryRun,
            'matched' => \count($jobIds),
            'deleted' => $dryRun ? 0 : \count($jobIds),
            'job_ids' => $jobIds,
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    /**
     * POST /job/  — Create or update a job record.
     */
    public function setjobById(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $body = (array) ($request->getParsedBody() ?? []);

        if (empty($body)) {
            return $response->withStatus(400);
        }

        // Allowed writable fields (stdout/stderr are no longer columns)
        $allowed = ['app_id', 'company_id', 'runtemplate_id', 'executor', 'exitcode',
            'begin', 'end', 'schedule', 'schedule_type', 'launched_by', 'app_version',
            'pid', 'task_id', 'env', 'command'];
        $data = array_intersect_key($body, array_flip($allowed));

        if (isset($body['id']) && (int) $body['id'] > 0) {
            // Update
            $jobId = (int) $body['id'];

            if (!$this->fetchJob($jobId)) {
                return $response->withStatus(404);
            }

            if (!empty($data)) {
                $setClauses = implode(', ', array_map(static fn ($k) => "{$k} = :{$k}", array_keys($data)));
                $stmt = $this->pdo->prepare("UPDATE job SET {$setClauses} WHERE id = :id");
                $stmt->bindValue(':id', $jobId, \PDO::PARAM_INT);

                foreach ($data as $key => $value) {
                    $stmt->bindValue(":{$key}", $value);
                }

                $stmt->execute();
            }
        } else {
            // Insert
            if (empty($data)) {
                return $response->withStatus(400);
            }

            $cols = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(static fn ($k) => ":{$k}", array_keys($data)));
            $stmt = $this->pdo->prepare("INSERT INTO job ({$cols}) VALUES ({$placeholders})");

            foreach ($data as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }

            $stmt->execute();
            $jobId = (int) $this->pdo->lastInsertId();
        }

        $job = $this->fetchJob($jobId);
        $response->getBody()->write((string) json_encode($job));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    // -----------------------------------------------------------------------
    // Private helpers
    // -----------------------------------------------------------------------

    /**
     * True when the authenticated user (set by BasicAuthenticator as the
     * `authenticated_user_id` request attribute) holds the `admin` or
     * `super_admin` RBAC role, via MultiFlexi\Rbac (multiflexi-core).
     */
    private function currentUserHasAdminRole(ServerRequestInterface $request): bool
    {
        $userId = (int) $request->getAttribute('authenticated_user_id');

        if ($userId <= 0) {
            return false;
        }

        return (new \MultiFlexi\Rbac())->userHasRole($userId, ['admin', 'super_admin']);
    }

    private function fetchJob(int $jobId): ?array
    {
        $stmt = $this->pdo->prepare(<<<'EOD'
SELECT id, app_id, company_id, runtemplate_id, executor, exitcode,
                    begin, end, schedule, schedule_type, launched_by, app_version,
                    pid, task_id
             FROM job WHERE id = :id
EOD, );
        $stmt->bindValue(':id', $jobId, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }

    /**
     * Concatenate all lines of a given type for a job.
     */
    private function assembleOutput(int $jobId, string $type): string
    {
        $stmt = $this->pdo->prepare(<<<'EOD'
SELECT line FROM job_output_lines
             WHERE job_id = :job_id AND type = :type
             ORDER BY seq ASC, id ASC
EOD, );
        $stmt->bindValue(':job_id', $jobId, \PDO::PARAM_INT);
        $stmt->bindValue(':type', $type);
        $stmt->execute();

        return implode('', array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'line'));
    }

    /**
     * Return all output lines for a job (all types), ordered by sequence.
     */
    private function fetchOutputLines(int $jobId): array
    {
        $stmt = $this->pdo->prepare(<<<'EOD'
SELECT id, seq, type, line, created_at
             FROM job_output_lines
             WHERE job_id = :job_id
             ORDER BY seq ASC, id ASC
EOD, );
        $stmt->bindValue(':job_id', $jobId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
