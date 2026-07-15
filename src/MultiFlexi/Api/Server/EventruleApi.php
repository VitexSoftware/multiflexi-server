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
 * Description of EventruleApi.
 *
 * @author Vitex <info@vitexsoftware.cz>
 *
 * @no-named-arguments
 */
class EventruleApi extends \MultiFlexi\Api\Server\AbstractEventruleApi
{
    public \MultiFlexi\EventRule $engine;

    /**
     * EventRule Handler Engine.
     */
    public function __construct()
    {
        $this->engine = new \MultiFlexi\EventRule();
        $this->engine->limit = 20;
    }

    /**
     * EventRule Info by ID.
     */
    public function getEventRuleById(ServerRequestInterface $request, ResponseInterface $response, int $eventRuleId, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($eventRuleId);

        if (!$this->engine->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'EventRule not found'], $suffix);
        }

        return DefaultApi::prepareResponse($response, $this->engine->getData(), $suffix, null, 'event_rule');
    }

    /**
     * All EventRules.
     */
    public function listEventRules(ServerRequestInterface $request, ResponseInterface $response, string $suffix): ResponseInterface
    {
        $eventRulesList = [];
        $queryParams = $request->getQueryParams();
        $limit = (\array_key_exists('limit', $queryParams)) ? $queryParams['limit'] : $this->engine->limit;

        foreach ($this->engine->listingQuery()->limit($limit) as $eventRule) {
            $eventRulesList[] = $eventRule;
        }

        return DefaultApi::prepareResponse($response, $eventRulesList, $suffix, null, 'event_rule');
    }

    /**
     * POST setEventRuleById
     * Summary: Create or Update EventRule
     * Notes: Create or Update EventRule (event-to-RunTemplate mapping).
     */
    public function setEventRuleById(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $eventRuleId = (\array_key_exists('eventRuleId', $queryParams)) ? (int) $queryParams['eventRuleId'] : null;
        $body = json_decode($request->getBody()->getContents(), true) ?? [];

        if ($eventRuleId) {
            $this->engine->loadFromSQL($eventRuleId);
        }

        foreach ($body as $key => $value) {
            $this->engine->setDataValue($key, $value);
        }

        $success = $this->engine->dbsync();

        return DefaultApi::prepareResponse($response->withStatus($success ? 201 : 400), $this->engine->getData(), 'json', null, 'event_rule');
    }

    /**
     * DELETE deleteEventRuleById
     * Summary: Delete EventRule by ID.
     */
    public function deleteEventRuleById(ServerRequestInterface $request, ResponseInterface $response, int $eventRuleId, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($eventRuleId);

        if (!$this->engine->getMyKey()) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'EventRule not found'], $suffix);
        }

        $this->engine->deleteFromSQL(['id' => $eventRuleId]);

        return DefaultApi::prepareResponse($response, ['success' => true], $suffix);
    }
}
