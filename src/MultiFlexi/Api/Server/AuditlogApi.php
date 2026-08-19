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

class AuditlogApi extends AbstractAuditlogApi
{
    public \MultiFlexi\Security\AuditLogEntry $engine;

    public function __construct()
    {
        $this->engine = new \MultiFlexi\Security\AuditLogEntry();
    }

    /**
     * GET /auditlog.{suffix}
     * List audit log entries, optionally filtered by user_id, entity_type, entity_id, action, and date range.
     */
    public function listAuditLog(ServerRequestInterface $request, ResponseInterface $response, string $suffix): ResponseInterface
    {
        $params = $request->getQueryParams();
        $query = $this->engine->listingQuery()->orderBy('id DESC');

        if (!empty($params['user_id']) && is_numeric($params['user_id'])) {
            $query->where('user_id', (int) $params['user_id']);
        }

        if (!empty($params['entity_type'])) {
            $query->where('entity_type', $params['entity_type']);
        }

        if (!empty($params['entity_id']) && is_numeric($params['entity_id'])) {
            $query->where('entity_id', (int) $params['entity_id']);
        }

        if (!empty($params['action'])) {
            $query->where('action', $params['action']);
        }

        if (!empty($params['from'])) {
            $query->where('created_at >= ?', $params['from']);
        }

        if (!empty($params['to'])) {
            $query->where('created_at <= ?', $params['to']);
        }

        $limit = !empty($params['limit']) && is_numeric($params['limit']) ? (int) $params['limit'] : 50;
        $query->limit($limit);

        $entries = array_values($query->fetchAll());

        return DefaultApi::prepareResponse($response, $entries, $suffix, null, 'auditlog');
    }

    /**
     * GET /auditlog/{auditLogId}.{suffix}
     * Return a single audit log entry.
     */
    public function getAuditLogById(ServerRequestInterface $request, ResponseInterface $response, int $auditLogId, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($auditLogId);
        $entryData = $this->engine->getData();

        if (empty($entryData)) {
            $body = $response->getBody();
            $body->write(json_encode(['error' => 'Audit Log entry not found'], \JSON_THROW_ON_ERROR));

            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        return DefaultApi::prepareResponse($response, $entryData, $suffix, null, 'auditlog');
    }
}
