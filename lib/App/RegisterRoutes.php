<?php

/**
 * MultiFlexi API
 * PHP version 7.4.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 */

/**
 * This is an example of using OAuth2 Application Flow in a specification to describe security to your API.
 * The version of the OpenAPI document: 1.0.0
 * Contact: vitezslav.dvorak@spojenet.cz
 * Generated by: https://github.com/openapitools/openapi-generator.git.
 */

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

namespace MultiFlexi\Api\App;

use Dyorg\TokenAuthentication;
use MultiFlexi\Api\Auth\BasicAuthenticator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotImplementedException;

/**
 * RegisterRoutes Class Doc Comment.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 */
class RegisterRoutes
{
    /**
     * @var array[] list of all api operations
     */
    private array $operations = [
        [
            'httpMethod' => 'POST',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/app/',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractAppApi',
            'userClassname' => 'AppApi',
            'operationId' => 'setAppById',
            'responses' => [
                '201' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "application created or updated",
  "content" : {
    "application/json" : {
      "schema" : {
        "$ref" : "#/components/schemas/App"
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid ID supplied"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
                '404' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "App not found"
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/app/{appId}.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractAppApi',
            'userClassname' => 'AppApi',
            'operationId' => 'getAppById',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "successful operation",
  "content" : {
    "application/json" : {
      "schema" : {
        "$ref" : "#/components/schemas/App"
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid ID supplied"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
                '404' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "App not found"
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/apps.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractAppApi',
            'userClassname' => 'AppApi',
            'operationId' => 'listApps',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "successful operation",
  "content" : {
    "application/json" : {
      "schema" : {
        "type" : "array",
        "items" : {
          "$ref" : "#/components/schemas/App"
        }
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid status value"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'POST',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/company/',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractCompanyApi',
            'userClassname' => 'CompanyApi',
            'operationId' => 'setCompanyById',
            'responses' => [
                '201' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Company created or updated",
  "content" : {
    "application/json" : {
      "schema" : {
        "$ref" : "#/components/schemas/Company"
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid ID supplied"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
                '404' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Company not found"
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/company/{companyId}.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractCompanyApi',
            'userClassname' => 'CompanyApi',
            'operationId' => 'getCompanyById',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "successful operation",
  "content" : {
    "application/json" : {
      "schema" : {
        "$ref" : "#/components/schemas/Company"
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid ID supplied"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
                '404' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Company not found"
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/companies.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractCompanyApi',
            'userClassname' => 'CompanyApi',
            'operationId' => 'listCompanies',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "successful operation",
  "content" : {
    "application/json" : {
      "schema" : {
        "type" : "array",
        "items" : {
          "$ref" : "#/components/schemas/Company"
        }
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid status value"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractDefaultApi',
            'userClassname' => 'DefaultApi',
            'operationId' => 'rootGet',
            'responses' => [
                '301' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "redirect to index.html"
}
EOD,
                ],
            ],
            'authMethods' => [
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/index.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractDefaultApi',
            'userClassname' => 'DefaultApi',
            'operationId' => 'getApiIndex',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "OK"
}
EOD,
                ],
            ],
            'authMethods' => [
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/login.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractDefaultApi',
            'userClassname' => 'DefaultApi',
            'operationId' => 'loginSuffixGet',
            'responses' => [
                '201' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "OK"
}
EOD,
                ],
            ],
            'authMethods' => [
            ],
        ],
        [
            'httpMethod' => 'POST',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/login.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractDefaultApi',
            'userClassname' => 'DefaultApi',
            'operationId' => 'loginSuffixPost',
            'responses' => [
                '201' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "OK"
}
EOD,
                ],
            ],
            'authMethods' => [
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/ping.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractDefaultApi',
            'userClassname' => 'DefaultApi',
            'operationId' => 'pingSuffixGet',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "OK"
}
EOD,
                ],
            ],
            'authMethods' => [
            ],
        ],
        [
            'httpMethod' => 'POST',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/job/',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractJobApi',
            'userClassname' => 'JobApi',
            'operationId' => 'setjobById',
            'responses' => [
                '201' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "record created or updated",
  "content" : {
    "application/json" : {
      "schema" : {
        "$ref" : "#/components/schemas/Job"
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid ID supplied"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
                '404' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "App not found"
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/job/{jobId}.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractJobApi',
            'userClassname' => 'JobApi',
            'operationId' => 'getjobById',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "successful operation",
  "content" : {
    "application/json" : {
      "schema" : {
        "$ref" : "#/components/schemas/Job"
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid ID supplied"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
                '404' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "App not found"
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/jobs.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractJobApi',
            'userClassname' => 'JobApi',
            'operationId' => 'listjobs',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "successful operation",
  "content" : {
    "application/json" : {
      "schema" : {
        "type" : "array",
        "items" : {
          "$ref" : "#/components/schemas/Job"
        }
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid status value"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'POST',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/runtemplate',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractRuntemplateApi',
            'userClassname' => 'RuntemplateApi',
            'operationId' => 'setRunTemplateById',
            'responses' => [
                '201' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "RunTemplate created or updated",
  "content" : {
    "application/json" : {
      "schema" : {
        "$ref" : "#/components/schemas/RunTemplate"
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid ID supplied"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
                '404' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "RunTemplate not found"
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/runtemplate/{runTemplateId}.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractRuntemplateApi',
            'userClassname' => 'RuntemplateApi',
            'operationId' => 'getRunTemplateById',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "successful operation",
  "content" : {
    "application/json" : {
      "schema" : {
        "$ref" : "#/components/schemas/RunTemplate"
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid ID supplied"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
                '404' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "RunTemplate not found"
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/runtemplates.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractRuntemplateApi',
            'userClassname' => 'RuntemplateApi',
            'operationId' => 'listRunTemplates',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "successful operation",
  "content" : {
    "application/json" : {
      "schema" : {
        "type" : "array",
        "items" : {
          "$ref" : "#/components/schemas/RunTemplate"
        }
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid status value"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'POST',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/user/',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractUserApi',
            'userClassname' => 'UserApi',
            'operationId' => 'setUserById',
            'responses' => [
                '201' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "User created or updated",
  "content" : {
    "application/json" : {
      "schema" : {
        "$ref" : "#/components/schemas/User"
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid ID supplied"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
                '404' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "User not found"
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/user/{userId}.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractUserApi',
            'userClassname' => 'UserApi',
            'operationId' => 'getUserById',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "successful operation",
  "content" : {
    "application/json" : {
      "schema" : {
        "$ref" : "#/components/schemas/User"
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid ID supplied"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
                '404' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "User not found"
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
        [
            'httpMethod' => 'GET',
            'basePathWithoutHost' => '/VitexSoftware/MultiFlexi/1.0.0',
            'path' => '/users.{suffix}',
            'apiPackage' => 'MultiFlexi\Api\ApiServer',
            'classname' => 'AbstractUserApi',
            'userClassname' => 'UserApi',
            'operationId' => 'listUsers',
            'responses' => [
                '200' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "successful operation",
  "content" : {
    "application/json" : {
      "schema" : {
        "type" : "array",
        "items" : {
          "$ref" : "#/components/schemas/User"
        }
      }
    }
  }
}
EOD,
                ],
                '400' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Invalid status value"
}
EOD,
                ],
                '401' => [
                    'jsonSchema' => <<<'EOD'
{
  "description" : "Authentication information is missing or invalid",
  "headers" : {
    "WWW_Authenticate" : {
      "style" : "simple",
      "explode" : false,
      "schema" : {
        "type" : "string"
      }
    }
  }
}
EOD,
                ],
            ],
            'authMethods' => [
                // http security schema named 'basicAuth'
                [
                    'type' => 'http',
                    'isBasic' => true,
                    'isBearer' => false,
                    'isApiKey' => false,
                    'isOAuth' => false,
                ],
            ],
        ],
    ];

    /**
     * Add routes to Slim app.
     *
     * @param \Slim\App $app Pre-configured Slim application instance
     *
     * @throws HttpNotImplementedException When implementation class doesn't exists
     */
    public function __invoke(\Slim\App $app): void
    {
        $app->options('/{routes:.*}', static function (ServerRequestInterface $request, ResponseInterface $response) {
            // CORS Pre-Flight OPTIONS Request Handler
            return $response;
        });

        // create mock middleware factory
        /** @var \Psr\Container\ContainerInterface */
        $container = $app->getContainer();
        /** @var null|\OpenAPIServer\Mock\OpenApiDataMockerRouteMiddlewareFactory */
        $mockMiddlewareFactory = null;

        if ($container->has(\OpenAPIServer\Mock\OpenApiDataMockerRouteMiddlewareFactory::class)) {
            // I know, anti-pattern. Don't retrieve dependency directly from container
            $mockMiddlewareFactory = $container->get(\OpenAPIServer\Mock\OpenApiDataMockerRouteMiddlewareFactory::class);
        }

        foreach ($this->operations as $operation) {
            $callback = static function (ServerRequestInterface $request) use ($operation): void {
                $message = "How about extending {$operation['classname']} by {$operation['apiPackage']}\\{$operation['userClassname']} class implementing {$operation['operationId']} as a {$operation['httpMethod']} method?";

                throw new HttpNotImplementedException($request, $message);
            };
            $middlewares = [];

            if (class_exists("\\{$operation['apiPackage']}\\{$operation['userClassname']}")) {
                // Notice how we register the controller using the class name?
                // PHP-DI will instantiate the class for us only when it's actually necessary
                $callback = ["\\{$operation['apiPackage']}\\{$operation['userClassname']}", $operation['operationId']];
            }

            if ($mockMiddlewareFactory) {
                $mockSchemaResponses = array_map(static function ($item) {
                    return json_decode($item['jsonSchema'], true);
                }, $operation['responses']);
                $middlewares[] = $mockMiddlewareFactory->create($mockSchemaResponses);
            }

            $route = $app->map(
                [$operation['httpMethod']],
                "{$operation['basePathWithoutHost']}{$operation['path']}",
                $callback,
            )->setName($operation['operationId']);

            // Add authentication middleware based on the operation's authMethods
            if ($operation['authMethods']) {
                foreach ($operation['authMethods'] as $authMethod) {
                    if ($authMethod['isBasic']) {
                        $route->add(new TokenAuthentication([
                            'path' => '/',
                            'authenticator' => new BasicAuthenticator(),
                            'regex' => '/Basic\s+(.*)$/i',
                            'header' => 'Authorization',
                            'parameter' => null,
                            'cookie' => null,
                            'argument' => null,
                            'attribute' => 'authorization_token',
                            'error' => ['MultiFlexi\Api\Auth\BasicAuthenticator', 'handleUnauthorized'],
                        ]));
                    }
                }
            }

            foreach ($middlewares as $middleware) {
                $route->add($middleware);
            }
        }
    }
}
