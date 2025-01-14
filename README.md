# MultiFlexi - PHP Slim 4 Server library for MultiFlexi API

* [OpenAPI Generator](https://openapi-generator.tech)
* [Slim 4 Documentation](https://www.slimframework.com/docs/v4/)

This server has been generated with [Slim PSR-7](https://github.com/slimphp/Slim-Psr7) implementation.
[PHP-DI](https://php-di.org/doc/frameworks/slim.html) package used as dependency container.

## Requirements

* Web server with URL rewriting
* PHP 7.4 or newer

This package contains `.htaccess` for Apache configuration.
If you use another server(Nginx, HHVM, IIS, lighttpd) check out [Web Servers](https://www.slimframework.com/docs/v3/start/web-servers.html) doc.

## Installation via [Composer](https://getcomposer.org/)

Navigate into your project's root directory and execute the bash command shown below.
This command downloads the Slim Framework and its third-party dependencies into your project's `vendor/` directory.
```bash
$ composer install
```

## Add configs

[PHP-DI package](https://php-di.org/doc/getting-started.html) helps to decouple configuration from implementation. App loads configuration files in straight order(`$env` can be `prod` or `dev`):
1. `config/$env/default.inc.php` (contains safe values, can be committed to vcs)
2. `config/$env/config.inc.php` (user config, excluded from vcs, can contain sensitive values, passwords etc.)
3. `lib/App/RegisterDependencies.php`

## Start devserver

Run the following command in terminal to start localhost web server, assuming `./php-slim-server/public/` is public-accessible directory with `index.php` file:
```bash
$ php -S localhost:8888 -t php-slim-server/public
```
> **Warning** This web server was designed to aid application development.
> It may also be useful for testing purposes or for application demonstrations that are run in controlled environments.
> It is not intended to be a full-featured web server. It should not be used on a public network.

## Tests

### PHPUnit

This package uses PHPUnit 8 or 9(depends from your PHP version) for unit testing.
[Test folder](tests) contains templates which you can fill with real test assertions.
How to write tests read at [2. Writing Tests for PHPUnit - PHPUnit 8.5 Manual](https://phpunit.readthedocs.io/en/8.5/writing-tests-for-phpunit.html).

#### Run

Command | Target
---- | ----
`$ composer test` | All tests
`$ composer test-apis` | Apis tests
`$ composer test-models` | Models tests

#### Config

Package contains fully functional config `./phpunit.xml.dist` file. Create `./phpunit.xml` in root folder to override it.

Quote from [3. The Command-Line Test Runner — PHPUnit 8.5 Manual](https://phpunit.readthedocs.io/en/8.5/textui.html#command-line-options):

> If phpunit.xml or phpunit.xml.dist (in that order) exist in the current working directory and --configuration is not used, the configuration will be automatically read from that file.

### PHP CodeSniffer

[PHP CodeSniffer Documentation](https://github.com/squizlabs/PHP_CodeSniffer/wiki). This tool helps to follow coding style and avoid common PHP coding mistakes.

#### Run

```bash
$ composer phpcs
```

#### Config

Package contains fully functional config `./phpcs.xml.dist` file. It checks source code against PSR-1 and PSR-2 coding standards.
Create `./phpcs.xml` in root folder to override it. More info at [Using a Default Configuration File](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Advanced-Usage#using-a-default-configuration-file)

### PHPLint

[PHPLint Documentation](https://github.com/overtrue/phplint). Checks PHP syntax only.

#### Run

```bash
$ composer phplint
```

## Show errors

Switch your app environment to development
- When using with some webserver => in `public/.htaccess` file:
```ini
## .htaccess
<IfModule mod_env.c>
    SetEnv APP_ENV 'development'
</IfModule>
```

- Or when using whatever else, set `APP_ENV` environment variable like this:
```bash
export APP_ENV=development
```
or simply
```bash
export APP_ENV=dev
```

## Mock Server
Since this feature should be used for development only, change environment to `development` and send additional HTTP header `X-MultiFlexi\Api-Mock: ping` with any request to get mocked response.
CURL example:
```console
curl --request GET \
    --url 'http://localhost:8888/v2/pet/findByStatus?status=available' \
    --header 'accept: application/json' \
    --header 'X-MultiFlexi\Api-Mock: ping'
[{"id":-8738629417578509312,"category":{"id":-4162503862215270400,"name":"Lorem ipsum dol"},"name":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem i","photoUrls":["Lor"],"tags":[{"id":-3506202845849391104,"name":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet, consectet"}],"status":"pending"}]
```

Used packages:
* [Openapi Data Mocker](https://github.com/ybelenko/openapi-data-mocker) - first implementation of OAS3 fake data generator.
* [Openapi Data Mocker Server Middleware](https://github.com/ybelenko/openapi-data-mocker-server-middleware) - PSR-15 HTTP server middleware.
* [Openapi Data Mocker Interfaces](https://github.com/ybelenko/openapi-data-mocker-interfaces) - package with mocking interfaces.

## Logging

Build contains pre-configured [`monolog/monolog`](https://github.com/Seldaek/monolog) package. Make sure that `logs` folder is writable.
Add required log handlers/processors/formatters in `lib/App/RegisterDependencies.php`.

## API Endpoints

All URIs are relative to *https://virtserver.swaggerhub.com/VitexSoftware/MultiFlexi/1.0.0*

> Important! Do not modify abstract API controllers directly! Instead extend them by implementation classes like:

```php
// src/Api/PetApi.php

namespace MultiFlexi\Api\Server;

use MultiFlexi\Api\Server\AbstractPetApi;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class PetApi extends AbstractPetApi
{
    public function addPet(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        // your implementation of addPet method here
    }
}
```

When you need to inject dependencies into API controller check [PHP-DI - Controllers as services](https://github.com/PHP-DI/Slim-Bridge#controllers-as-services) guide.

Place all your implementation classes in `./src` folder accordingly.
For instance, when abstract class located at `./lib/Api/AbstractPetApi.php` you need to create implementation class at `./src/Api/PetApi.php`.

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AbstractAppApi* | **setAppById** | **POST** /app/ | Create or Update Application
*AbstractAppApi* | **getAppById** | **GET** /app/{appId}.{suffix} | Get App by ID
*AbstractAppApi* | **listApps** | **GET** /apps.{suffix} | Show All Apps
*AbstractCompanyApi* | **setCompanyById** | **POST** /company/ | Create or Update Company
*AbstractCompanyApi* | **getCompanyById** | **GET** /company/{companyId}.{suffix} | Get Company by ID
*AbstractCompanyApi* | **listCompanies** | **GET** /companies.{suffix} | Show All Companies
*AbstractDefaultApi* | **rootGet** | **GET** / | Redirect to index
*AbstractDefaultApi* | **getAllCredentialTypes** | **GET** /credential_types.{suffix} | Get All Credential Types
*AbstractDefaultApi* | **getAllTopics** | **GET** /topics.{suffix} | Get All Topics
*AbstractDefaultApi* | **getAllUserCredentials** | **GET** /credentials.{suffix} | Get All User Credentials
*AbstractDefaultApi* | **getApiIndex** | **GET** /index.{suffix} | Endpoints listing
*AbstractDefaultApi* | **getCredential** | **GET** /credential/{credentialId}.{suffix} | Get User Credentials
*AbstractDefaultApi* | **getCredentialType** | **GET** /credential_type/{credentialTypeID}.{suffix} | Get Credential Type by ID
*AbstractDefaultApi* | **getJobsStatus** | **GET** /jobs/status.{suffix} | Get Jobs Status
*AbstractDefaultApi* | **getTopic** | **GET** /topic/{topicId}.{suffix} | Get Topic by ID
*AbstractDefaultApi* | **loginSuffixGet** | **GET** /login.{suffix} | Return User's token
*AbstractDefaultApi* | **loginSuffixPost** | **POST** /login.{suffix} | Return User's token
*AbstractDefaultApi* | **pingSuffixGet** | **GET** /ping.{suffix} | job heartbeat operation
*AbstractDefaultApi* | **statusSuffixGet** | **GET** /status.{suffix} | Get API status
*AbstractDefaultApi* | **updateCredentialType** | **POST** /credential_type/{credentialTypeID}.{suffix} | Update Credential Type
*AbstractDefaultApi* | **updateCredentials** | **POST** /credential/{credentialId}.{suffix} | Update Credentials
*AbstractDefaultApi* | **updateTopic** | **POST** /topic/{topicId}.{suffix} | Update Topic
*AbstractJobApi* | **setjobById** | **POST** /job/ | Create or Update job record
*AbstractJobApi* | **getjobById** | **GET** /job/{jobId}.{suffix} | Get job by ID
*AbstractJobApi* | **listjobs** | **GET** /jobs.{suffix} | Show All jobs
*AbstractRuntemplateApi* | **setRunTemplateById** | **POST** /runtemplate | Create or Update RunTemplate
*AbstractRuntemplateApi* | **getRunTemplateById** | **GET** /runtemplate/{runTemplateId}.{suffix} | Get RunTemplate by ID
*AbstractRuntemplateApi* | **listRunTemplates** | **GET** /runtemplates.{suffix} | Show All RunTemplates
*AbstractUserApi* | **setUserById** | **POST** /user/ | Create or Update User
*AbstractUserApi* | **getUserById** | **GET** /user/{userId}.{suffix} | Get User by ID
*AbstractUserApi* | **listUsers** | **GET** /users.{suffix} | Show All Users


## Models

* MultiFlexi\Api\Model\App
* MultiFlexi\Api\Model\Company
* MultiFlexi\Api\Model\ConfField
* MultiFlexi\Api\Model\Configuration
* MultiFlexi\Api\Model\Credential
* MultiFlexi\Api\Model\CredentialType
* MultiFlexi\Api\Model\Customer
* MultiFlexi\Api\Model\GetCredentialType200Response
* MultiFlexi\Api\Model\GetTopic200Response
* MultiFlexi\Api\Model\Job
* MultiFlexi\Api\Model\JobsStatus
* MultiFlexi\Api\Model\RunTemplate
* MultiFlexi\Api\Model\Status
* MultiFlexi\Api\Model\Tag
* MultiFlexi\Api\Model\Topic
* MultiFlexi\Api\Model\UpdateCredentials201Response
* MultiFlexi\Api\Model\User


## Authentication

### Security schema `basicAuth`
> Important! To make Basic authentication work you need to extend [\MultiFlexi\Api\Auth\AbstractAuthenticator](./lib/Auth/AbstractAuthenticator.php) class by [\MultiFlexi\Api\Auth\BasicAuthenticator](./src/Auth/BasicAuthenticator.php) class.

### Advanced middleware configuration
Ref to used Slim Token Middleware [dyorg/slim-token-authentication](https://github.com/dyorg/slim-token-authentication/tree/1.x#readme)
