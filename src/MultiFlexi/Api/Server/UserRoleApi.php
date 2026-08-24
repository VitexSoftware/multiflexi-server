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
 * Delegates to MultiFlexi\Rbac (multiflexi-core), the single source of
 * truth also used by `multiflexi-cli user-role:set`.
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

        $roles = (new \MultiFlexi\Rbac())->getUserRoleDetails($userId);

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

        try {
            $finalRoles = (new \MultiFlexi\Rbac())->setUserRoles($userId, $roleNames, $replace, $assignedBy);
        } catch (\InvalidArgumentException $e) {
            return DefaultApi::prepareResponse($response->withStatus(400), ['error' => $e->getMessage()], 'json');
        } catch (\Throwable $e) {
            return DefaultApi::prepareResponse($response->withStatus(400), ['error' => 'Failed to set user roles: '.$e->getMessage()], 'json');
        }

        return DefaultApi::prepareResponse($response, [
            'user_id' => $userId,
            'replace' => $replace,
            'roles' => array_column($finalRoles, 'name'),
        ], 'json');
    }
}
