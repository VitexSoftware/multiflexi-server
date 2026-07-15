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
 * Description of EventsourceApi.
 *
 * @author Vitex <info@vitexsoftware.cz>
 *
 * @no-named-arguments
 */
class EventsourceApi extends \MultiFlexi\Api\Server\AbstractEventsourceApi
{
    public \MultiFlexi\EventSource $engine;

    /**
     * EventSource Handler Engine.
     */
    public function __construct()
    {
        $this->engine = new \MultiFlexi\EventSource();
        $this->engine->limit = 20;
    }

    /**
     * EventSource Info by ID.
     */
    public function getEventSourceById(ServerRequestInterface $request, ResponseInterface $response, int $eventSourceId, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($eventSourceId);

        if (!$this->engine->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'EventSource not found'], $suffix);
        }

        return DefaultApi::prepareResponse($response, $this->engine->getData(), $suffix, null, 'event_source');
    }

    /**
     * All EventSources.
     */
    public function listEventSources(ServerRequestInterface $request, ResponseInterface $response, string $suffix): ResponseInterface
    {
        $eventSourcesList = [];
        $queryParams = $request->getQueryParams();
        $limit = (\array_key_exists('limit', $queryParams)) ? $queryParams['limit'] : $this->engine->limit;

        foreach ($this->engine->listingQuery()->limit($limit) as $eventSource) {
            $eventSourcesList[] = $eventSource;
        }

        return DefaultApi::prepareResponse($response, $eventSourcesList, $suffix, null, 'event_source');
    }

    /**
     * POST setEventSourceById
     * Summary: Create or Update EventSource
     * Notes: Create or Update EventSource (webhook adapter database connection).
     */
    public function setEventSourceById(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $eventSourceId = (\array_key_exists('eventSourceId', $queryParams)) ? (int) $queryParams['eventSourceId'] : null;
        $body = json_decode($request->getBody()->getContents(), true) ?? [];

        if ($eventSourceId) {
            $this->engine->loadFromSQL($eventSourceId);
        }

        foreach ($body as $key => $value) {
            $this->engine->setDataValue($key, $value);
        }

        $success = $this->engine->dbsync();

        return DefaultApi::prepareResponse($response->withStatus($success ? 201 : 400), $this->engine->getData(), 'json', null, 'event_source');
    }

    /**
     * DELETE deleteEventSourceById
     * Summary: Delete EventSource by ID.
     */
    public function deleteEventSourceById(ServerRequestInterface $request, ResponseInterface $response, int $eventSourceId, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($eventSourceId);

        if (!$this->engine->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'EventSource not found'], $suffix);
        }

        $this->engine->deleteFromSQL(['id' => $eventSourceId]);

        return DefaultApi::prepareResponse($response, ['success' => true], $suffix);
    }

    /**
     * POST testEventSourceConnection
     * Summary: Test EventSource connection.
     */
    public function testEventSourceConnection(ServerRequestInterface $request, ResponseInterface $response, int $eventSourceId, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($eventSourceId);

        if (!$this->engine->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'EventSource not found'], $suffix);
        }

        try {
            $reachable = $this->engine->isReachable();
            $result = ['reachable' => $reachable];
        } catch (\Throwable $e) {
            $result = ['reachable' => false, 'error' => $e->getMessage()];
        }

        return DefaultApi::prepareResponse($response, $result, $suffix);
    }
}
