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

use MultiFlexi\Flow\Flow;
use MultiFlexi\Flow\FlowRepository;
use MultiFlexi\Flow\FlowRun;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Flow API — sync Node-RED Deploy graphs and inspect runs.
 *
 * @author Vitex <info@vitexsoftware.cz>
 *
 * @no-named-arguments
 */
class FlowApi extends AbstractFlowApi
{
    public FlowRepository $repository;

    public function __construct()
    {
        $this->repository = new FlowRepository();
    }

    /**
     * POST /flow/ — upsert a flow graph from Node-RED Deploy.
     */
    public function syncFlow(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true) ?? [];

        try {
            $result = $this->repository->syncFromDeploy($body);
        } catch (\InvalidArgumentException $e) {
            return DefaultApi::prepareResponse(
                $response->withStatus(400),
                ['error' => $e->getMessage()],
                'json',
            );
        } catch (\Throwable $e) {
            return DefaultApi::prepareResponse(
                $response->withStatus(500),
                ['error' => $e->getMessage()],
                'json',
            );
        }

        return DefaultApi::prepareResponse($response->withStatus(201), $result, 'json', null, 'flow');
    }

    public function getFlowById(ServerRequestInterface $request, ResponseInterface $response, int $flowId, string $suffix): ResponseInterface
    {
        try {
            $data = $this->repository->exportFlow($flowId);
        } catch (\Throwable $e) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => $e->getMessage()], $suffix);
        }

        return DefaultApi::prepareResponse($response, $data, $suffix, null, 'flow');
    }

    public function listFlows(ServerRequestInterface $request, ResponseInterface $response, string $suffix): ResponseInterface
    {
        $engine = new Flow();
        $queryParams = $request->getQueryParams();
        $limit = (\array_key_exists('limit', $queryParams)) ? (int) $queryParams['limit'] : 50;
        $list = [];

        foreach ($engine->listingQuery()->limit($limit) as $row) {
            $row['enabled'] = (bool) ($row['enabled'] ?? false);
            $list[] = $row;
        }

        return DefaultApi::prepareResponse($response, $list, $suffix, null, 'flow');
    }

    public function listFlowRuns(ServerRequestInterface $request, ResponseInterface $response, int $flowId, string $suffix): ResponseInterface
    {
        $engine = new FlowRun();
        $queryParams = $request->getQueryParams();
        $limit = (\array_key_exists('limit', $queryParams)) ? (int) $queryParams['limit'] : 50;
        $list = [];

        foreach ($engine->listingQuery()->where('flow_id', $flowId)->orderBy('id DESC')->limit($limit) as $row) {
            $list[] = $row;
        }

        return DefaultApi::prepareResponse($response, $list, $suffix, null, 'flow_run');
    }

    public function cancelFlowRun(ServerRequestInterface $request, ResponseInterface $response, int $flowRunId, string $suffix): ResponseInterface
    {
        $ok = $this->repository->cancelRun($flowRunId);

        if (!$ok) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'Flow run not found'], $suffix);
        }

        return DefaultApi::prepareResponse($response, ['success' => true, 'id' => $flowRunId], $suffix);
    }
}
