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

/**
 * This is an example of using OAuth2 Application Flow in a specification to describe security to your API.
 * The version of the OpenAPI document: 1.1.0
 * Contact: vitezslav.dvorak@spojenet.cz
 * Generated by: https://github.com/openapitools/openapi-generator.git.
 */

/**
 * NOTE: This class is auto generated by the openapi generator program.
 * https://github.com/openapitools/openapi-generator
 * Do not edit the class manually.
 * Extend this class with your controller. You can inject dependencies via class constructor,.
 *
 * @see https://github.com/PHP-DI/Slim-Bridge basic example.
 */

namespace MultiFlexi\Api\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotImplementedException;

/**
 * AbstractAppApi Class Doc Comment.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 */
abstract class AbstractAppApi
{
    /**
     * GET getAppById
     * Summary: Get App by ID
     * Notes: Returns a single App
     * Output-Formats: [application/json].
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param int                    $appId    ID of app to return
     * @param string                 $suffix   force format suffix
     *
     * @throws HttpNotImplementedException to force implementation class to override this method
     */
    public function getAppById(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $appId,
        string $suffix
    ): ResponseInterface {
        $queryParams = $request->getQueryParams();
        $limit = (\array_key_exists('limit', $queryParams)) ? $queryParams['limit'] : null;
        $message = 'How about implementing getAppById as a GET method in MultiFlexi\\Api\\Server\\AppApi class?';

        throw new HttpNotImplementedException($request, $message);
    }

    /**
     * GET listApps
     * Summary: Show All Apps
     * Notes: All apps registeres
     * Output-Formats: [application/json].
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param string                 $suffix   force format suffix
     *
     * @throws HttpNotImplementedException to force implementation class to override this method
     */
    public function listApps(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $suffix
    ): ResponseInterface {
        $queryParams = $request->getQueryParams();
        $limit = (\array_key_exists('limit', $queryParams)) ? $queryParams['limit'] : null;
        $message = 'How about implementing listApps as a GET method in MultiFlexi\\Api\\Server\\AppApi class?';

        throw new HttpNotImplementedException($request, $message);
    }

    /**
     * POST setAppById
     * Summary: Create or Update Application
     * Notes: Create or Update App by ID
     * Output-Formats: [application/json].
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     *
     * @throws HttpNotImplementedException to force implementation class to override this method
     */
    public function setAppById(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $queryParams = $request->getQueryParams();
        $appId = (\array_key_exists('appId', $queryParams)) ? $queryParams['appId'] : null;
        $limit = (\array_key_exists('limit', $queryParams)) ? $queryParams['limit'] : null;
        $message = 'How about implementing setAppById as a POST method in MultiFlexi\\Api\\Server\\AppApi class?';

        throw new HttpNotImplementedException($request, $message);
    }
}
