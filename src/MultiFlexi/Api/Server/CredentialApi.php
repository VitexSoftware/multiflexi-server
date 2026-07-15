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
 * Description of CredentialApi.
 *
 * @author Vitex <info@vitexsoftware.cz>
 *
 * @no-named-arguments
 */
class CredentialApi extends \MultiFlexi\Api\Server\AbstractCredentialApi
{
    public \MultiFlexi\Credential $engine;

    /**
     * Credential Handler Engine.
     */
    public function __construct()
    {
        $this->engine = new \MultiFlexi\Credential();
        $this->engine->limit = 20;
    }

    /**
     * GET getCredential
     * Summary: Get User Credentials
     * Notes: Retrieve user credentials based on provided token.
     */
    public function getCredential(ServerRequestInterface $request, ResponseInterface $response, int $credentialId, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($credentialId);

        if (!$this->engine->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'Credential not found'], $suffix);
        }

        return DefaultApi::prepareResponse($response, $this->engine->getData(), $suffix, null, 'credential');
    }

    /**
     * GET getAllUserCredentials
     * Summary: Get All User Credentials.
     */
    public function getAllUserCredentials(ServerRequestInterface $request, ResponseInterface $response, string $suffix): ResponseInterface
    {
        $credentialsList = [];
        $queryParams = $request->getQueryParams();
        $limit = (\array_key_exists('limit', $queryParams)) ? $queryParams['limit'] : $this->engine->limit;

        foreach ($this->engine->listingQuery()->limit($limit) as $credential) {
            $credentialsList[] = $credential;
        }

        return DefaultApi::prepareResponse($response, $credentialsList, $suffix, null, 'credential');
    }

    /**
     * POST updateCredentials
     * Summary: Update Credentials.
     */
    public function updateCredentials(ServerRequestInterface $request, ResponseInterface $response, int $credentialId, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($credentialId);

        if (!$this->engine->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'Credential not found'], $suffix);
        }

        $body = json_decode($request->getBody()->getContents(), true) ?? [];

        foreach ($body as $key => $value) {
            $this->engine->setDataValue($key, $value);
        }

        $success = $this->engine->dbsync();

        return DefaultApi::prepareResponse($response->withStatus($success ? 201 : 400), $this->engine->getData(), $suffix, null, 'credential');
    }
}
