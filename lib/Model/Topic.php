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
 * Topic.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 */
class Topic extends BaseModel
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
  "properties" : {
    "id" : {
      "type" : "integer",
      "format" : "int64",
      "example" : 10
    },
    "name" : {
      "maxLength" : 32,
      "type" : "string"
    },
    "description" : {
      "maxLength" : 255,
      "type" : "string"
    },
    "color" : {
      "maxLength" : 7,
      "type" : "string",
      "example" : "#000000"
    },
    "DatCreate" : {
      "type" : "string",
      "format" : "date-time"
    },
    "DatUpdate" : {
      "type" : "string"
    }
  }
}
SCHEMA;
}
