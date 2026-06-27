<?php

declare(strict_types=1);

// Composer InstalledVersions registry (package: composer)
require_once '/usr/share/php/Composer/InstalledVersions.php';

// Slim 4 framework (php-slim)
require_once '/usr/share/php/Slim/autoload.php';
// Slim PSR-7 (php-slim-psr7)
require_once '/usr/share/php/Slim/Psr7/autoload.php';

// PHP-DI container + invoker (php-di, php-di-invoker)
require_once '/usr/share/php/PhpDi/PhpDi/autoload.php';
require_once '/usr/share/php/PhpDi/Invoker/autoload.php';

// PHP-DI Slim bridge (php-di-slim-bridge)
spl_autoload_register(static function (string $class): void {
    $prefix = 'DI\\Bridge\\Slim\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $file = '/usr/share/php/DI/Bridge/Slim/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Monolog (php-monolog)
require_once '/usr/share/php/Monolog/autoload.php';

// PSR interfaces (php-psr-http-message, php-psr-http-factory, php-psr-container, php-psr-log)
require_once '/usr/share/php/Psr/Http/Message/autoload.php';
require_once '/usr/share/php/Psr/Http/Message/factory-autoload.php';
require_once '/usr/share/php/Psr/Container/autoload.php';
require_once '/usr/share/php/Psr/Log/autoload.php';

// PSR-15: RequestHandlerInterface + MiddlewareInterface
// (php-psr-http-server-handler, php-psr-http-server-middleware)
spl_autoload_register(static function (string $class): void {
    $prefix = 'Psr\\Http\\Server\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $file = '/usr/share/php/Psr/Http/Server/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Fast router (php-nikic-fast-route)
require_once '/usr/share/php/FastRoute/autoload.php';

// HTTP message util (php-fig-http-message-util)
require_once '/usr/share/php/Fig/HttpMessageUtil/autoload.php';

// Laravel serializable closure (php-laravel-serializable-closure, pulled by php-di)
require_once '/usr/share/php/Laravel/SerializableClosure/autoload.php';

// Neomerx CORS (php-neomerx-cors-psr7)
spl_autoload_register(static function (string $class): void {
    $prefix = 'Neomerx\\Cors\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $file = '/usr/share/php/Neomerx/Cors/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Dyorg Slim Token Authentication (php-dyorg-slim-token-authentication)
spl_autoload_register(static function (string $class): void {
    $prefix = 'Dyorg\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $file = '/usr/share/php/Dyorg/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Tuupola middleware stack: basic-auth + callable-handler + http-factory
// (php-jimtools-basic-auth, php-tuupola-callable-handler, php-tuupola-http-factory)
spl_autoload_register(static function (string $class): void {
    $prefix = 'Tuupola\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $file = '/usr/share/php/Tuupola/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// ybelenko OpenAPI data mocker + server middleware
// (php-ybelenko-openapi-data-mocker, php-ybelenko-openapi-data-mocker-server-middleware)
spl_autoload_register(static function (string $class): void {
    $prefix = 'OpenAPIServer\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $file = '/usr/share/php/OpenAPIServer/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// MultiFlexi core library (php-vitexsoftware-multiflexi-core)
require_once '/usr/share/php/MultiFlexi/autoload.php';

// MultiFlexi API library classes (this package)
require_once '/usr/share/php/MultiFlexiApi/autoload.php';

// InstalledVersions: 'unknown', '0.0.0', and 'library' are build-time
// placeholders replaced by debian/rules via sed.
(function (): void {
    $versions = [];
    foreach (\Composer\InstalledVersions::getAllRawData() as $d) {
        $versions = array_merge($versions, $d['versions'] ?? []);
    }
    $name    = 'unknown';
    $version = '0.0.0';
    $type    = 'library';
    $versions[$name] = ['pretty_version' => $version, 'version' => $version,
        'reference' => null, 'type' => $type, 'install_path' => __DIR__,
        'aliases' => [], 'dev_requirement' => false];
    \Composer\InstalledVersions::reload([
        'root' => ['name' => $name, 'pretty_version' => $version, 'version' => $version,
            'reference' => null, 'type' => $type, 'install_path' => __DIR__,
            'aliases' => [], 'dev' => false],
        'versions' => $versions,
    ]);
})();
