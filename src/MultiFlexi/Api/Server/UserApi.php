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
 * Description of UserApi.
 *
 * @author Vitex <info@vitexsoftware.cz>
 *
 * @no-named-arguments
 */
class UserApi extends \MultiFlexi\Api\Server\AbstractUserApi
{
    public \MultiFlexi\User $engine;

    /**
     * User Handler Engine.
     */
    public function __construct()
    {
        $this->engine = new \MultiFlexi\User();
        $this->engine->limit = 20;
    }

    /**
     * User Info by ID.
     *
     * @url http://localhost/EASE/MultiFlexi/src/api/VitexSoftware/MultiFlexi/1.0.0/user/1
     */
    public function getUserById(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, int $userId, string $suffix): \Psr\Http\Message\ResponseInterface
    {
        $this->engine->loadFromSQL($userId);

        if (!$this->engine->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'User not found'], $suffix);
        }

        $userData = $this->engine->getData();

        switch ($suffix) {
            case 'html':
                $userData = [array_keys($userData), $userData];

                break;

            default:
                break;
        }

        return DefaultApi::prepareResponse($response, $userData, $suffix, null, 'user');
    }

    /**
     * All Users.
     *
     * @url http://localhost/EASE/MultiFlexi/src/api/VitexSoftware/MultiFlexi/1.0.0/users
     */
    public function listUsers(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, string $suffix): \Psr\Http\Message\ResponseInterface
    {
        $usersList = [];
        $queryParams = $request->getQueryParams();
        $limit = (\array_key_exists('limit', $queryParams)) ? $queryParams['limit'] : $this->engine->limit;

        foreach ($this->engine->listingQuery()->limit($limit) as $user) {
            $usersList[] = $user;
        }

        return DefaultApi::prepareResponse($response, $usersList, $suffix, null, 'user');
    }

    /**
     * POST setUserById
     * Summary: Create or Update User
     * Notes: Create or Update User by ID.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     */
    public function setUserById(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $userId = (\array_key_exists('userId', $queryParams)) ? $queryParams['userId'] : null;
        $userInfo = ['id' => $userId, 'success' => $this->engine->dbsync($queryParams)];

        return DefaultApi::prepareResponse($response, $userInfo, 'json', 'user'.$userId);
    }
}
