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

require_once __DIR__.'/../vendor/autoload.php';

/**
 * MultiFlexi\* / Ease\* core classes are not a Composer dependency of this
 * repo - they come from the system-installed php-vitexsoftware-multiflexi-core
 * package (see debian/autoload.php), matching how the API actually runs in
 * production. Load it here too so tests/Integration can use the real core
 * classes (DBEngine, User, Company, ...) the same way the handlers do.
 */
if (is_readable('/usr/share/php/MultiFlexi/autoload.php')) {
    require_once '/usr/share/php/MultiFlexi/autoload.php';
}
