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

class TaskApi extends AbstractTaskApi
{
    public \MultiFlexi\Task $engine;

    public function __construct()
    {
        $this->engine = new \MultiFlexi\Task();
    }

    /**
     * GET /tasks.{suffix}
     * List tasks, optionally filtered by state and/or runtemplate_id.
     */
    public function listTasks(ServerRequestInterface $request, ResponseInterface $response, string $suffix): ResponseInterface
    {
        $params = $request->getQueryParams();
        $query = $this->engine->listingQuery()->orderBy('id DESC');

        if (!empty($params['state'])) {
            $query->where('state', $params['state']);
        }

        if (!empty($params['runtemplate_id']) && is_numeric($params['runtemplate_id'])) {
            $query->where('runtemplate_id', (int) $params['runtemplate_id']);
        }

        if (!empty($params['from'])) {
            $query->where('window_start >= ?', $params['from']);
        }

        if (!empty($params['to'])) {
            $query->where('window_end <= ?', $params['to']);
        }

        $limit = !empty($params['limit']) && is_numeric($params['limit']) ? (int) $params['limit'] : 50;
        $query->limit($limit);

        $tasks = array_values($query->fetchAll());

        return DefaultApi::prepareResponse($response, $tasks, $suffix, null, 'task');
    }

    /**
     * GET /task/{taskId}.{suffix}
     * Return a single task with its embedded job attempt history.
     */
    public function getTaskById(ServerRequestInterface $request, ResponseInterface $response, int $taskId, string $suffix): ResponseInterface
    {
        $this->engine->loadFromSQL($taskId);
        $taskData = $this->engine->getData();

        if (empty($taskData)) {
            $body = $response->getBody();
            $body->write(json_encode(['error' => 'Task not found'], \JSON_THROW_ON_ERROR));

            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $taskData['jobs'] = $this->engine->getJobs();

        return DefaultApi::prepareResponse($response, $taskData, $suffix, null, 'task');
    }
}
