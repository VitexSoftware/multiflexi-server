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
 * Description of CredentialTypeApi.
 *
 * @author Vitex <info@vitexsoftware.cz>
 *
 * @no-named-arguments
 */
class CredentialTypeApi extends \MultiFlexi\Api\Server\AbstractCredentialTypeApi
{
    public \MultiFlexi\CredentialType $engine;

    /**
     * CredentialType Handler Engine.
     */
    public function __construct()
    {
        $this->engine = new \MultiFlexi\CredentialType();
        $this->engine->limit = 20;
    }

    /**
     * GET getCredentialType
     * Summary: Get Credential Type by ID.
     */
    public function getCredentialType(ServerRequestInterface $request, ResponseInterface $response, int $credentialTypeID, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($credentialTypeID);

        if (!$this->engine->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'Credential Type not found'], $suffix);
        }

        return DefaultApi::prepareResponse($response, $this->engine->getData(), $suffix, null, 'credential_type');
    }

    /**
     * GET getAllCredentialTypes
     * Summary: Get All Credential Types.
     */
    public function getAllCredentialTypes(ServerRequestInterface $request, ResponseInterface $response, string $suffix): ResponseInterface
    {
        $credentialTypesList = [];
        $queryParams = $request->getQueryParams();
        $limit = (\array_key_exists('limit', $queryParams)) ? $queryParams['limit'] : $this->engine->limit;

        foreach ($this->engine->listingQuery()->limit($limit) as $credentialType) {
            $credentialTypesList[] = $credentialType;
        }

        return DefaultApi::prepareResponse($response, $credentialTypesList, $suffix, null, 'credential_type');
    }

    /**
     * POST updateCredentialType
     * Summary: Update Credential Type.
     */
    public function updateCredentialType(ServerRequestInterface $request, ResponseInterface $response, int $credentialTypeID, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($credentialTypeID);

        if (!$this->engine->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'Credential Type not found'], $suffix);
        }

        $body = json_decode($request->getBody()->getContents(), true) ?? [];

        foreach ($body as $key => $value) {
            $this->engine->setDataValue($key, $value);
        }

        $success = $this->engine->dbsync();

        return DefaultApi::prepareResponse($response->withStatus($success ? 201 : 400), $this->engine->getData(), $suffix, null, 'credential_type');
    }
}
