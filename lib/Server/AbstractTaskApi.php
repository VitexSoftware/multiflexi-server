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

abstract class AbstractTaskApi
{
    /**
     * GET listTasks
     * Summary: List Tasks
     * Notes: Returns a list of tasks optionally filtered by state or runtemplate.
     *
     * @throws HttpNotImplementedException to force implementation class to override this method
     */
    public function listTasks(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $suffix
    ): ResponseInterface {
        $message = 'How about implementing listTasks as a GET method in MultiFlexi\\Api\\Server\\TaskApi class?';

        throw new HttpNotImplementedException($request, $message);
    }

    /**
     * GET getTaskById
     * Summary: Get Task by ID
     * Notes: Returns a single task with its job attempt history.
     *
     * @throws HttpNotImplementedException to force implementation class to override this method
     */
    public function getTaskById(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $taskId,
        string $suffix
    ): ResponseInterface {
        $message = 'How about implementing getTaskById as a GET method in MultiFlexi\\Api\\Server\\TaskApi class?';

        throw new HttpNotImplementedException($request, $message);
    }
}
