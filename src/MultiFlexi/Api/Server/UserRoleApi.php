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
 * Description of UserRoleApi.
 *
 * RBAC roles for users, backed by the rbac_roles / rbac_user_roles tables
 * (formalized in multiflexi-database migration 20260715015632_rbac_roles.php).
 * Mirrors the logic already proven in `multiflexi-cli user-role:set`.
 *
 * @author Vitex <info@vitexsoftware.cz>
 *
 * @no-named-arguments
 */
class UserRoleApi extends \MultiFlexi\Api\Server\AbstractUserRoleApi
{
    /**
     * GET getUserRoles
     * Summary: Get RBAC roles for a user.
     */
    public function getUserRoles(ServerRequestInterface $request, ResponseInterface $response, int $userId, string $suffix): ResponseInterface
    {
        $user = new \MultiFlexi\User($userId);

        if (empty($user->getData())) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'User not found'], $suffix);
        }

        $roles = $this->loadUserRoles($userId);

        return DefaultApi::prepareResponse($response, $roles, $suffix, null, 'rbac_role');
    }

    /**
     * POST setUserRoles
     * Summary: Set RBAC roles for a user
     * Notes: Replace (or extend) the RBAC roles assigned to a user.
     */
    public function setUserRoles(ServerRequestInterface $request, ResponseInterface $response, int $userId): ResponseInterface
    {
        $user = new \MultiFlexi\User($userId);

        if (empty($user->getData())) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'User not found'], 'json');
        }

        $queryParams = $request->getQueryParams();
        $replace = !\array_key_exists('replace', $queryParams) || filter_var($queryParams['replace'], \FILTER_VALIDATE_BOOLEAN);

        $body = json_decode($request->getBody()->getContents(), true) ?? [];
        $roleNames = array_values(array_unique(array_filter(array_map('strval', $body['roles'] ?? []))));
        $assignedBy = isset($body['assigned_by']) && is_numeric($body['assigned_by']) ? (int) $body['assigned_by'] : null;

        $rolesEngine = new \MultiFlexi\DBEngine();
        $rolesEngine->myTable = 'rbac_roles';
        $availableRoles = [];

        foreach ($rolesEngine->listingQuery()->where('is_active', 1) as $role) {
            $availableRoles[$role['name']] = (int) $role['id'];
        }

        $missingRoles = array_values(array_diff($roleNames, array_keys($availableRoles)));

        if ($missingRoles) {
            return DefaultApi::prepareResponse($response->withStatus(400), ['error' => 'Unknown role(s): '.implode(', ', $missingRoles)], 'json');
        }

        $targetRoleIds = array_map(static fn (string $name): int => $availableRoles[$name], $roleNames);

        $pdo = $rolesEngine->getPdo();

        $pdo->beginTransaction();

        try {
            if ($replace) {
                if (empty($targetRoleIds)) {
                    $pdo->prepare('DELETE FROM rbac_user_roles WHERE user_id = ?')->execute([$userId]);
                } else {
                    $placeholders = implode(',', array_fill(0, \count($targetRoleIds), '?'));
                    $pdo->prepare('DELETE FROM rbac_user_roles WHERE user_id = ? AND role_id NOT IN ('.$placeholders.')')
                        ->execute(array_merge([$userId], $targetRoleIds));
                }
            }

            foreach ($targetRoleIds as $roleId) {
                $pdo->prepare(
                    'INSERT INTO rbac_user_roles (user_id, role_id, assigned_by) VALUES (?, ?, ?) '
                    .'ON DUPLICATE KEY UPDATE assigned_by = VALUES(assigned_by), assigned_at = CURRENT_TIMESTAMP',
                )->execute([$userId, $roleId, $assignedBy]);
            }

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();

            return DefaultApi::prepareResponse($response->withStatus(400), ['error' => 'Failed to set user roles: '.$e->getMessage()], 'json');
        }

        $finalRoles = $this->loadUserRoles($userId);

        return DefaultApi::prepareResponse($response, [
            'user_id' => $userId,
            'replace' => $replace,
            'roles' => array_column($finalRoles, 'name'),
        ], 'json');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadUserRoles(int $userId): array
    {
        $engine = new \MultiFlexi\DBEngine();
        $stmt = $engine->getPdo()->prepare(
            'SELECT r.id, r.name, r.display_name, ur.assigned_at, ur.expires_at '
            .'FROM rbac_roles r JOIN rbac_user_roles ur ON ur.role_id = r.id '
            .'WHERE ur.user_id = ? AND r.is_active = 1 ORDER BY r.name',
        );
        $stmt->execute([$userId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }
}
