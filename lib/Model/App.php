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

/**
 * This is an example of using OAuth2 Application Flow in a specification to describe security to your API.
 * The version of the OpenAPI document: 1.1.0
 * Contact: vitezslav.dvorak@spojenet.cz
 * Generated by: https://github.com/openapitools/openapi-generator.git.
 */

/**
 * NOTE: This class is auto generated by the openapi generator program.
 * https://github.com/openapitools/openapi-generator.
 */

namespace MultiFlexi\Api\Model;

use MultiFlexi\Api\BaseModel;

/**
 * App.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 */
class App extends BaseModel
{
    /**
     * @var string Models namespace.
     *             Can be required for data deserialization when model contains referenced schemas.
     */
    protected const MODELS_NAMESPACE = '\MultiFlexi\Api\Model';

    /**
     * @var string Constant with OAS schema of current class.
     *             Should be overwritten by inherited class.
     */
    protected const MODEL_SCHEMA = <<<'SCHEMA'
{
  "required" : [ "executable", "name" ],
  "type" : "object",
  "properties" : {
    "id" : {
      "type" : "integer",
      "format" : "int64",
      "example" : 10
    },
    "name" : {
      "type" : "string",
      "example" : "matcher"
    },
    "executable" : {
      "type" : "string",
      "example" : "multiflexi-probe"
    },
    "tags" : {
      "type" : "array",
      "xml" : {
        "wrapped" : true
      },
      "items" : {
        "$ref" : "#/components/schemas/Tag"
      }
    },
    "status" : {
      "type" : "string",
      "description" : "App status in the store",
      "enum" : [ "available", "pending", "sold" ]
    }
  },
  "xml" : {
    "name" : "App"
  },
  "x-swagger-router-model" : "com.vitexsoftware.multiflexi.model.App"
}
SCHEMA;
}
