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
 * Description of UserCompanyApi.
 *
 * @author Vitex <info@vitexsoftware.cz>
 *
 * @no-named-arguments
 */
class UserCompanyApi extends \MultiFlexi\Api\Server\AbstractUserCompanyApi
{
    /**
     * Assign a user to a company.
     *
     * POST /company/{companyId}/user/
     */
    public function assignUserToCompany(ServerRequestInterface $request, ResponseInterface $response, int $companyId): ResponseInterface
    {
        $company = new \MultiFlexi\Company();
        $company->loadFromSQL($companyId);

        if (!$company->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'Company not found'], 'json');
        }

        $body = json_decode($request->getBody()->getContents(), true);
        $userId = isset($body['user_id']) ? (int) $body['user_id'] : 0;
        $role = $body['role'] ?? 'viewer';

        if ($userId <= 0) {
            return DefaultApi::prepareResponse($response->withStatus(400), ['error' => 'user_id is required'], 'json');
        }

        $companyUser = new \MultiFlexi\CompanyUser($company);

        if (!$companyUser->assignUser($userId, $role)) {
            return DefaultApi::prepareResponse($response->withStatus(400), ['error' => 'Assignment failed'], 'json');
        }

        $assignment = $companyUser->getAssigned()->where('user_id', $userId)->fetch();

        return DefaultApi::prepareResponse($response->withStatus(201), $assignment, 'json', null, 'company_user');
    }

    /**
     * List users assigned to a company.
     *
     * GET /company/{companyId}/users.{suffix}
     */
    public function listCompanyUsers(ServerRequestInterface $request, ResponseInterface $response, int $companyId, string $suffix): ResponseInterface
    {
        $company = new \MultiFlexi\Company();
        $company->loadFromSQL($companyId);

        if (!$company->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'Company not found'], $suffix);
        }

        $companyUser = new \MultiFlexi\CompanyUser($company);
        $usersList = [];

        foreach ($companyUser->getAssigned() as $assignment) {
            $usersList[] = $assignment;
        }

        return DefaultApi::prepareResponse($response, $usersList, $suffix, null, 'company_user');
    }

    /**
     * Remove a user from a company.
     *
     * DELETE /company/{companyId}/user/{userId}
     */
    public function unassignUserFromCompany(ServerRequestInterface $request, ResponseInterface $response, int $companyId, int $userId): ResponseInterface
    {
        $company = new \MultiFlexi\Company();
        $company->loadFromSQL($companyId);

        if (!$company->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'Company not found'], 'json');
        }

        $companyUser = new \MultiFlexi\CompanyUser($company);
        $companyUser->removeUser($userId);

        return DefaultApi::prepareResponse($response, ['success' => true], 'json');
    }
}
