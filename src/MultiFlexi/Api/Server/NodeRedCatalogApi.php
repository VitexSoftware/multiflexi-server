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

use MultiFlexi\Application;
use MultiFlexi\Company;
use MultiFlexi\CompanyUser;
use MultiFlexi\Credential;
use MultiFlexi\CredentialType;
use MultiFlexi\Rbac;
use MultiFlexi\RunTemplate;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * RBAC-scoped catalog for Node-RED editor palettes.
 *
 * Returns the same shape as multiflexi-eventor's NodeRedCatalog push
 * (companies / runtemplates / credentials), filtered to entities the
 * authenticated user may see via company_user assignment. Users with the
 * admin or super_admin RBAC role receive the full catalog.
 */
class NodeRedCatalogApi
{
    /**
     * GET /nodered/catalog.{suffix}
     */
    public function getCatalog(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $suffix = 'json',
    ): ResponseInterface {
        $user = \Ease\Shared::user();
        $userId = $user ? (int) $user->getUserID() : 0;

        if ($userId <= 0) {
            $attrId = (int) $request->getAttribute('authenticated_user_id');
            $userId = $attrId > 0 ? $attrId : 0;
        }

        if ($userId <= 0) {
            $response->getBody()->write((string) json_encode(['error' => 'Authentication required']));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $companyIds = $this->accessibleCompanyIds($userId);
        $payload = [
            'companies' => $this->companies($companyIds),
            'runtemplates' => $this->runtemplates($companyIds),
            'credentials' => $this->credentials($companyIds),
            'executors' => $this->executors(),
            'user_id' => $userId,
        ];

        return DefaultApi::prepareResponse($response, $payload, $suffix, null, 'nodered-catalog');
    }

    /**
     * GET /nodered/executors.{suffix}
     *
     * Lists MultiFlexi executor classes installed on this host
     * (same discovery as the web UI ExecutorSelect).
     */
    public function getExecutors(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $suffix = 'json',
    ): ResponseInterface {
        return DefaultApi::prepareResponse(
            $response,
            ['executors' => $this->executors()],
            $suffix,
            null,
            'nodered-executors',
        );
    }

    /**
     * Installed MultiFlexi\Executor\* classes available on this host.
     *
     * @return list<array{id: string, name: string, description: string}>
     */
    private function executors(): array
    {
        \Ease\Functions::loadClassesInNamespace('MultiFlexi\\Executor');
        $classes = \Ease\Functions::classesInNamespace('MultiFlexi\\Executor');
        $result = [];

        foreach ($classes as $shortName) {
            $class = '\\MultiFlexi\\Executor\\'.$shortName;

            if (!class_exists($class)) {
                continue;
            }

            $result[] = [
                'id' => $shortName,
                'name' => method_exists($class, 'name') ? (string) $class::name() : $shortName,
                'description' => method_exists($class, 'description') ? (string) $class::description() : $shortName,
            ];
        }

        usort($result, static fn (array $a, array $b): int => strcmp($a['id'], $b['id']));

        return $result;
    }

    /**
     * @return list<int>|null null means unrestricted (admin)
     */
    private function accessibleCompanyIds(int $userId): ?array
    {
        $rbac = new Rbac();

        if ($rbac->userHasRole($userId, ['admin', 'super_admin'])) {
            return null;
        }

        $companyUser = new CompanyUser();
        $rows = $companyUser->listingQuery()
            ->leftJoin('company ON company.id = company_user.company_id')
            ->where('user_id', $userId)
            ->where('company.id IS NOT NULL')
            ->select(['company_user.company_id'])
            ->fetchAll();

        $ids = array_values(array_unique(array_filter(
            array_map('intval', array_column($rows, 'company_id')),
            static fn (int $id): bool => $id > 0,
        )));

        return $ids;
    }

    /**
     * @param list<int>|null $companyIds
     *
     * @return list<array<string, mixed>>
     */
    private function companies(?array $companyIds): array
    {
        $query = (new Company())->listingQuery();

        if (\is_array($companyIds)) {
            if ($companyIds === []) {
                return [];
            }

            $query->where('id', $companyIds);
        }

        $result = [];

        foreach ($query->fetchAll() as $row) {
            $result[] = [
                'id' => (int) $row['id'],
                'name' => (string) ($row['name'] ?? ''),
                'slug' => $row['slug'] ?? null,
                'code' => $row['code'] ?? null,
                'enabled' => (bool) ($row['enabled'] ?? false),
            ];
        }

        return $result;
    }

    /**
     * @param list<int>|null $companyIds
     *
     * @return list<array<string, mixed>>
     */
    private function runtemplates(?array $companyIds): array
    {
        if (\is_array($companyIds) && $companyIds === []) {
            return [];
        }

        $appMaps = $this->applicationMaps();
        $appUuids = $appMaps['uuids'];
        $appNames = $appMaps['names'];
        $query = (new RunTemplate())->listingQuery();

        if (\is_array($companyIds)) {
            $query->where('company_id', $companyIds);
        }

        $result = [];

        foreach ($query->fetchAll() as $row) {
            if (empty($row['active'])) {
                continue;
            }

            $appId = isset($row['app_id']) ? (int) $row['app_id'] : 0;

            $result[] = [
                'id' => (int) $row['id'],
                'name' => (string) ($row['name'] ?? ''),
                'company_id' => isset($row['company_id']) ? (int) $row['company_id'] : null,
                'app_id' => $appId,
                'app_uuid' => $appUuids[$appId] ?? null,
                'app_name' => $appNames[$appId] ?? null,
                'executor' => $row['executor'] ?? null,
                'interv' => $row['interv'] ?? null,
                'delay' => isset($row['delay']) ? (int) $row['delay'] : null,
                'active' => true,
            ];
        }

        return $result;
    }

    /**
     * @param list<int>|null $companyIds
     *
     * @return list<array<string, mixed>>
     */
    private function credentials(?array $companyIds): array
    {
        if (\is_array($companyIds) && $companyIds === []) {
            return [];
        }

        $typeNames = $this->credentialTypeNames();
        $query = (new Credential())->listingQuery();

        if (\is_array($companyIds)) {
            $query->where('company_id', $companyIds);
        }

        $result = [];

        foreach ($query->fetchAll() as $row) {
            $typeId = isset($row['credential_type_id']) ? (int) $row['credential_type_id'] : 0;

            $result[] = [
                'id' => (int) $row['id'],
                'name' => (string) ($row['name'] ?? ''),
                'company_id' => isset($row['company_id']) ? (int) $row['company_id'] : null,
                'credential_type_id' => $typeId ?: null,
                'type_name' => $typeNames[$typeId] ?? null,
            ];
        }

        return $result;
    }

    /**
     * @return array{uuids: array<int, ?string>, names: array<int, ?string>}
     */
    private function applicationMaps(): array
    {
        $uuids = [];
        $names = [];

        foreach ((new Application())->listingQuery()->fetchAll() as $row) {
            $id = (int) $row['id'];
            $uuids[$id] = $row['uuid'] ?? null;
            $names[$id] = $row['name'] ?? null;
        }

        return ['uuids' => $uuids, 'names' => $names];
    }

    /**
     * @return array<int, ?string>
     */
    private function applicationUuids(): array
    {
        return $this->applicationMaps()['uuids'];
    }

    /**
     * @return array<int, ?string>
     */
    private function applicationNames(): array
    {
        return $this->applicationMaps()['names'];
    }

    /**
     * @return array<int, ?string>
     */
    private function credentialTypeNames(): array
    {
        $map = [];

        foreach ((new CredentialType())->listingQuery()->fetchAll() as $row) {
            $map[(int) $row['id']] = $row['name'] ?? null;
        }

        return $map;
    }
}
