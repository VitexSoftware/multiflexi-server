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

class TaskApi extends AbstractTaskApi
{
    public \MultiFlexi\Task $engine;

    public function __construct()
    {
        $this->engine = new \MultiFlexi\Task();
    }

    /**
     * GET /tasks.{suffix}
     * List tasks, optionally filtered by state and/or runtemplate_id.
     */
    public function listTasks(ServerRequestInterface $request, ResponseInterface $response, string $suffix): ResponseInterface
    {
        $params = $request->getQueryParams();
        $query = $this->engine->listingQuery()->orderBy('id DESC');

        if (!empty($params['state'])) {
            $query->where('state', $params['state']);
        }

        if (!empty($params['runtemplate_id']) && is_numeric($params['runtemplate_id'])) {
            $query->where('runtemplate_id', (int) $params['runtemplate_id']);
        }

        if (!empty($params['from'])) {
            $query->where('window_start >= ?', $params['from']);
        }

        if (!empty($params['to'])) {
            $query->where('window_end <= ?', $params['to']);
        }

        $limit = !empty($params['limit']) && is_numeric($params['limit']) ? (int) $params['limit'] : 50;
        $query->limit($limit);

        $tasks = array_values($query->fetchAll());

        return DefaultApi::prepareResponse($response, $tasks, $suffix, null, 'task');
    }

    /**
     * GET /task/{taskId}.{suffix}
     * Return a single task with its embedded job attempt history.
     */
    public function getTaskById(ServerRequestInterface $request, ResponseInterface $response, int $taskId, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($taskId);
        $taskData = $this->engine->getData();

        if (empty($taskData)) {
            $body = $response->getBody();
            $body->write(json_encode(['error' => 'Task not found'], \JSON_THROW_ON_ERROR));

            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $taskData['jobs'] = $this->normalizeJobEnvs($this->engine->getJobs());

        return DefaultApi::prepareResponse($response, $taskData, $suffix, null, 'task');
    }

    /**
     * Task::getJobs() returns raw DB rows whose env column is still a PHP
     * serialized ConfigFields blob. Decode and redact it so JSON clients get
     * a plain object instead of an opaque serialize() string.
     *
     * @param list<array<string, mixed>> $jobs
     *
     * @return list<array<string, mixed>>
     */
    private function normalizeJobEnvs(array $jobs): array
    {
        foreach ($jobs as &$job) {
            if (!\array_key_exists('env', $job)) {
                continue;
            }

            $env = $job['env'];

            if (\is_string($env)) {
                $decoded = @unserialize($env);
                $env = $decoded === false && $env !== 'b:0;' ? $env : $decoded;
            }

            if ($env instanceof \MultiFlexi\ConfigFields) {
                $job['env'] = $env->getRedactedArray();
            } elseif (\is_array($env)) {
                $job['env'] = $env;
            } elseif ($env === null || $env === false) {
                $job['env'] = new \stdClass();
            } else {
                // Leave unexpected shapes as an empty object rather than a
                // raw serialize blob that breaks typed clients.
                $job['env'] = new \stdClass();
            }
        }

        unset($job);

        return $jobs;
    }
}
