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

spl_autoload_register(static function (string $class): void {
    $prefix = 'MultiFlexi\\Api\\';
    $baseDir = '/usr/share/php/MultiFlexiApi/';

    if (strncmp($prefix, $class, \strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, \strlen($prefix));
    $file = $baseDir.str_replace('\\', '/', $relativeClass).'.php';

    if (file_exists($file)) {
        require $file;
    }
});
