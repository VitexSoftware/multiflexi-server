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
use Slim\Exception\HttpNotImplementedException;

/**
 * Abstract Flow API (hand-written; Node-RED Deploy sync).
 */
abstract class AbstractFlowApi
{
    public function syncFlow(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        throw new HttpNotImplementedException($request, 'Implement syncFlow in FlowApi');
    }

    public function getFlowById(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $flowId,
        string $suffix
    ): ResponseInterface {
        throw new HttpNotImplementedException($request, 'Implement getFlowById in FlowApi');
    }

    public function listFlows(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $suffix
    ): ResponseInterface {
        throw new HttpNotImplementedException($request, 'Implement listFlows in FlowApi');
    }

    public function listFlowRuns(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $flowId,
        string $suffix
    ): ResponseInterface {
        throw new HttpNotImplementedException($request, 'Implement listFlowRuns in FlowApi');
    }

    public function cancelFlowRun(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $flowRunId,
        string $suffix
    ): ResponseInterface {
        throw new HttpNotImplementedException($request, 'Implement cancelFlowRun in FlowApi');
    }
}
