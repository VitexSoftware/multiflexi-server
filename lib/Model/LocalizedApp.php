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

namespace MultiFlexi\Api\Model;

use Envms\FluentPDO;
use Helpers\LocalizedStore;

class LocalizedApp extends App
{
    private $localizedStore;

    public function __construct(FluentPDO $pdo)
    {
        $this->localizedStore = new LocalizedStore($pdo);
    }

    /**
     * Get a localized app definition.
     */
    public function getLocalizedApp(int $appId, string $lang, string $defaultLang = 'cs')
    {
        $app = self::fetchAppById($appId);

        if (!$app) {
            return null;
        }

        $localizedName = $this->localizedStore->get('app', $appId, 'name', $lang, $defaultLang);
        $localizedDescription = $this->localizedStore->get('app', $appId, 'description', $lang, $defaultLang);

        return [
            'id' => $app['id'],
            'name' => $localizedName ?: $app['name'],
            'description' => $localizedDescription ?: $app['description'],
            'executable' => $app['executable'],
            'tags' => $app['tags'],
            'status' => $app['status'],
        ];
    }

    /**
     * Save a localized app definition.
     */
    public function saveLocalizedApp(array $appData, string $defaultLang = 'cs'): void
    {
        $appId = $appData['id'] ?? null;

        if (!$appId) {
            throw new \InvalidArgumentException('App ID is required.');
        }

        self::updateApp($appId, $appData);

        if (\is_array($appData['name'])) {
            foreach ($appData['name'] as $lang => $value) {
                $this->localizedStore->set('app', $appId, 'name', $lang, $value);
            }
        }

        if (\is_array($appData['description'])) {
            foreach ($appData['description'] as $lang => $value) {
                $this->localizedStore->set('app', $appId, 'description', $lang, $value);
            }
        }
    }

    private static function fetchAppById(int $appId): void
    {
        // Implement fetching app by ID using FluentPDO
    }

    private static function updateApp(int $appId, array $appData): void
    {
        // Implement updating app data using FluentPDO
    }
}
