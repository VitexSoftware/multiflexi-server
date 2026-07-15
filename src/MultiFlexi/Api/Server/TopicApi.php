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
 * Description of TopicApi.
 *
 * Backed directly by the `topic` table (id, topic, color) via DBEngine —
 * there is no dedicated ORM class for it: `MultiFlexi\Topic` is a separate,
 * unrelated capability-contract value object (see topic_concept.md), not a
 * DB-table entity. The `topic` column is exposed as `name` to match the
 * schema; the schema's `description` field has no backing column yet.
 *
 * @author Vitex <info@vitexsoftware.cz>
 *
 * @no-named-arguments
 */
class TopicApi extends \MultiFlexi\Api\Server\AbstractTopicApi
{
    public \MultiFlexi\DBEngine $engine;

    public function __construct()
    {
        $this->engine = new \MultiFlexi\DBEngine();
        $this->engine->myTable = 'topic';
    }

    /**
     * GET getTopic
     * Summary: Get Topic by ID.
     */
    public function getTopic(ServerRequestInterface $request, ResponseInterface $response, int $topicId, string $suffix): ResponseInterface
    {
        $row = $this->engine->listingQuery()->where('id', $topicId)->fetch();

        if (!$row) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'Topic not found'], $suffix);
        }

        return DefaultApi::prepareResponse($response, self::toApiShape($row), $suffix, null, 'topic');
    }

    /**
     * GET getAllTopics
     * Summary: Get All Topics.
     */
    public function getAllTopics(ServerRequestInterface $request, ResponseInterface $response, string $suffix): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $limit = (\array_key_exists('limit', $queryParams)) ? (int) $queryParams['limit'] : 100;

        $topicsList = [];

        foreach ($this->engine->listingQuery()->limit($limit) as $row) {
            $topicsList[] = self::toApiShape($row);
        }

        return DefaultApi::prepareResponse($response, $topicsList, $suffix, null, 'topic');
    }

    /**
     * POST updateTopic
     * Summary: Update Topic.
     */
    public function updateTopic(ServerRequestInterface $request, ResponseInterface $response, int $topicId, string $suffix): ResponseInterface
    {
        $row = $this->engine->listingQuery()->where('id', $topicId)->fetch();

        if (!$row) {
            return DefaultApi::prepareResponse($response->withStatus(404), ['error' => 'Topic not found'], $suffix);
        }

        $body = json_decode($request->getBody()->getContents(), true) ?? [];
        $update = [];

        if (\array_key_exists('name', $body)) {
            $update['topic'] = $body['name'];
        }

        if (\array_key_exists('color', $body)) {
            $update['color'] = $body['color'];
        }

        if ($update) {
            $this->engine->updateToSQL($update, ['id' => $topicId]);
        }

        $row = $this->engine->listingQuery()->where('id', $topicId)->fetch();

        return DefaultApi::prepareResponse($response->withStatus(201), self::toApiShape($row), $suffix, null, 'topic');
    }

    private static function toApiShape(array $row): array
    {
        return [
            'id' => $row['id'],
            'name' => $row['topic'],
            'color' => $row['color'],
        ];
    }
}
