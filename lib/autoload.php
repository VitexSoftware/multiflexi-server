<?php

declare(strict_types=1);

/**
 * Debian autoloader for php-vitexsoftware-multiflexi-server
 *
 * Registers PSR-4 autoloading for the MultiFlexi API library classes
 * installed under /usr/share/php/MultiFlexiApi/.
 */
spl_autoload_register(static function (string $class): void {
    $prefix = 'MultiFlexi\\Api\\';
    $baseDir = '/usr/share/php/MultiFlexiApi/';

    if (strncmp($prefix, $class, \strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, \strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
