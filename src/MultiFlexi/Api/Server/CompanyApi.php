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
 * Description of CompanyApi.
 *
 * @author Vitex <info@vitexsoftware.cz>
 *
 * @no-named-arguments
 */
class CompanyApi extends \MultiFlexi\Api\Server\AbstractCompanyApi
{
    public \MultiFlexi\Company $engine;

    /**
     * Company Handler Engine.
     */
    public function __construct()
    {
        $this->engine = new \MultiFlexi\Company();
        $this->engine->limit = 20;
    }

    /**
     * Company Info by ID.
     *
     * @url http://localhost/EASE/MultiFlexi/src/api/VitexSoftware/MultiFlexi/1.0.0/company/1
     */
    public function getCompanyById(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, int $companyId, string $suffix): \Psr\Http\Message\ResponseInterface
    {
        $this->engine->loadFromSQL($companyId);

        if (!$this->engine->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'Company not found'], $suffix);
        }

        $companyData = $this->engine->getData();
        $companyData['enabled'] = (bool) $companyData['enabled'];

        switch ($suffix) {
            case 'html':
                $companyData = [array_keys($companyData), $companyData];

                break;

            default:
                break;
        }

        return DefaultApi::prepareResponse($response, $companyData, $suffix, null, 'company');
    }

    /**
     * All Companies.
     *
     * @url http://localhost/EASE/MultiFlexi/src/api/VitexSoftware/MultiFlexi/1.0.0/companies
     */
    public function listCompanies(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, string $suffix): \Psr\Http\Message\ResponseInterface
    {
        $companiesList = [];
        $queryParams = $request->getQueryParams();
        $limit = (\array_key_exists('limit', $queryParams)) ? $queryParams['limit'] : $this->engine->limit;

        foreach ($this->engine->listingQuery()->limit($limit) as $company) {
            $company['enabled'] = (bool) $company['enabled'];
            $companiesList[] = $company;
        }

        return DefaultApi::prepareResponse($response, $companiesList, $suffix, null, 'company');
    }

    /**
     * POST setCompanyById
     * Summary: Create or Update Company
     * Notes: Create or Update Company by ID.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     */
    public function setCompanyById(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $companyId = (\array_key_exists('companyId', $queryParams)) ? $queryParams['companyId'] : null;
        $companyInfo = ['id' => $companyId, 'success' => $this->engine->dbsync($queryParams)];

        return DefaultApi::prepareResponse($response, $companyInfo, 'json', 'company'.$companyId);
    }
}
