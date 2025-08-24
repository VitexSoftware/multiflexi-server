# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

Repository: MultiFlexi API Server (PHP Slim 4 + PHP-DI, OpenAPI-generated)

What Warp should know first
- Language and framework: PHP 7.4+ or 8.x, Slim 4 with PHP-DI. Autoloaded namespace: MultiFlexi\Api\ (lib/).
- Entrypoint: public/index.php. It bootstraps the DI container, loads env-specific config (config/{dev,prod}/), registers routes/middlewares, and handles the request.
- Generated code: Most server scaffolding is OpenAPI-generated under lib/. Do not modify abstract API controllers in lib/ directly; put implementation classes in src/ mirroring names (see README: extend Abstract* APIs under lib/Api with concrete ones under src/Api).
- Config layering: For each env, load order is config/$env/default.inc.php, optional config/$env/config.inc.php (user-local, ignored), then dependency wiring in lib/App/RegisterDependencies.php.

Common commands
- Install dependencies
  - composer install
  - Or via make: make vendor
- Run test suites
  - All: composer test
  - API tests: composer test-apis
  - Model tests: composer test-models
  - Direct runner: vendor/bin/phpunit
  - Single test file: vendor/bin/phpunit tests/Api/DefaultApiTest.php
  - Filter by test name: vendor/bin/phpunit --filter "testMethodName"
- Static analysis (PHPStan)
  - make static-code-analysis
  - Generate/update baseline: make static-code-analysis-baseline
- Lint and coding standards
  - PHPCS check: composer phpcs (or vendor/bin/phpcs)
  - Code style fix: vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --diff --verbose
  - PHPLint (syntax only): composer phplint
- Local dev server (for quick manual checks only)
  - php -S localhost:8888 -t public
  - Set APP_ENV appropriately for verbose errors (see below).

Environment and runtime notes
- Environment selection: Set APP_ENV to dev or production.
  - bash: export APP_ENV=dev
  - Apache: public/.htaccess shows example using SetEnv.
- Logging: Monolog is configured; ensure logs/ is writable. Log path configured in config/*/default.inc.php.
- CORS: neomerx/cors-psr7 settings are defined in config/*/default.inc.php.
- Mock responses for development: With APP_ENV=dev, send header X-MultiFlexi\Api-Mock: ping (optional X-MultiFlexi\Api-Mock-Code) to receive mocked responses from openapi-data-mocker middleware.

Repository structure (high-level)
- public/index.php: Application bootstrap. Determines APP_ENV, loads config, builds DI container, registers middlewares and routes, dispatches request, emits response.
- config/dev|prod/*.inc.php: Pure value definitions merged into the DI container (error reporting, CORS, PDO DSN, logger, mocker callbacks). Add sensitive overrides in config/$env/config.inc.php (git-ignored by convention).
- lib/: OpenAPI-generated server glue (abstract API controllers under lib/Api, models under lib/Model, BaseModel, routing/middleware/DI registration helpers under lib/App, response emitter, etc.).
- src/: Place concrete implementations (e.g., src/Api/*) that extend abstract classes in lib/.
- tests/: PHPUnit test suites
  - tests/Api: Endpoint-oriented tests (suite name: "Apis")
  - tests/Model: Model tests (suite name: "Models"), plus BaseModelTest
- Tooling/config:
  - phpunit.xml.dist: Defines test suites Apis and Models; includes coverage for lib/.
  - phpcs.xml.dist: PSR-12 base with specific exclusions for generated code patterns.
  - phpstan-default.neon.dist (+ baseline): Analyses src and tests, level 6. Baseline file is phpstan-default-baseline.neon.
  - .php-cs-fixer.dist.php: Project CS config (Ergebnis rules with customizations).
  - .github/workflows/main.yml: CI runs composer install, phplint, phpunit (with coverage), phpcs across PHP 7.4/8.0/8.1.

How to extend endpoints (from README, condensed)
- Do not edit abstract API classes in lib/Api/Abstract*Api.php.
- Create corresponding implementation classes under src/Api/*Api.php that extend those abstract classes, then implement the required methods. PHP-DI can inject dependencies into these controllers (see PHP-DI Slim-Bridge controllers-as-services).

Running a focused workflow
- Typical development loop
  1) export APP_ENV=dev
  2) composer install
  3) php -S localhost:8888 -t public
  4) Implement or modify a controller in src/Api/*
  5) Run a single test: vendor/bin/phpunit tests/Api/DefaultApiTest.php --filter "testName"
  6) Lint: composer phpcs; fix style if needed with php-cs-fixer
  7) Static analysis: make static-code-analysis

Important excerpts to keep in mind
- README clarifies environment configs order, test suite names/commands, and the mocking headers.
- CI shows tools expected to pass: phplint, phpunit, phpcs.

Notes for Warp
- Prefer make targets when available (e.g., make static-code-analysis), otherwise use composer scripts or vendor/bin tools as listed above.
- When adding new source files, place concrete implementation in src/ and keep generated lib/ untouched. If adjusting code style or static analysis configurations, edit the corresponding *dist files and regenerate baselines when needed.

Regenerating server from OpenAPI spec
- Spec repo: ~/Projects/Multi/multiflexi-api (public: https://github.com/VitexSoftware/multiflexi-api)
  - Primary spec: openapi-schema.yaml
  - Generator config: server.yaml (uses templates/server) and openapitools.json (pins generator-cli version)
- Quick generate (recommended)
  - In the spec repo: make server
    - Internally runs: npx openapi-generator-cli generate -i openapi-schema.yaml -g php-slim4 -c server.yaml -o ~/Projects/Multi/multiflexi-server; then cd to this repo and run make cs
    - This will regenerate lib/ and tests/ here and then apply code style fixes. Your src/ remains intact for concrete implementations.
- Full reset and regenerate (destructive)
  - In the spec repo: ./regenerate.sh
    - WARNING: This removes in this repo: lib/, tests/, README.md, composer.json, and doc/ before re-generating. It does not remove src/. Use only when you intend to fully re-sync with the spec outputs.
- First-time setup for generator
  - In the spec repo run: npm install (package.json depends on @openapitools/openapi-generator-cli; openapitools.json pins the version).
- After generation
  - This repo’s make cs (triggered by make server) runs composer update here and formats code. If needed, you can manually run: composer install and vendor/bin/phpunit.
- Custom artifacts injected by templates
  - server.yaml arranges extra files into this repo (e.g., .php-cs-fixer.dist.php, phpstan-default*.neon, Makefile, LICENSE, composer.json). Review diffs after regeneration.

