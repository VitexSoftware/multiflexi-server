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
 * https://github.com/openapitools/openapi-generator
 * Do not edit the class manually.
 */

namespace MultiFlexi\Api\Auth;

use Dyorg\TokenAuthentication\Exceptions\UnauthorizedExceptionInterface;
use Dyorg\TokenAuthentication\TokenSearch;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * AbstractAuthenticator Class Doc Comment.
 *
 * @author  OpenAPI Generator team
 *
 * @see    https://github.com/openapitools/openapi-generator
 */
abstract class AbstractAuthenticator
{
    /**
     * @var null|string[] List of required scopes
     */
    protected ?array $requiredScope;

    /**
     * Authenticator constructor.
     *
     * @param null|string[] $requiredScope List of required scopes
     */
    public function __construct($requiredScope = null)
    {
        $this->requiredScope = $requiredScope;
    }

    /**
     * Makes the api key validation of your application.
     *
     * Just an example of implementation. Override this method to fit your needs
     *
     * @param ServerRequestInterface $request     HTTP request
     * @param TokenSearch            $tokenSearch Middleware instance which contains api key in token
     *
     * @throws UnauthorizedExceptionInterface when cannot parse token
     *
     * @return bool Must return either true or false
     */
    public function __invoke(ServerRequestInterface &$request, TokenSearch $tokenSearch)
    {
        /**
         * Try find authorization token via header, parameters, cookie or attribute
         * If token not found, return response with status 401 (unauthorized).
         */
        $token = $tokenSearch->getToken($request);

        /**
         * Verify if token is valid on database
         * If token isn't valid, expired or has insufficient scope must throw an UnauthorizedExceptionInterface.
         */
        $user = $this->getUserByToken($token);

        /**
         * Set authenticated user at attributes.
         */
        $request = $request->withAttribute('authenticated_user', $user);

        return true;
    }

    /**
     * Handles the response for unauthorized access attempts.
     *
     * This method is called when an access token is either not provided, invalid, or expired.
     * It constructs a response that includes an error message, the status code, and any other relevant information.
     *
     * @param ServerRequestInterface         $request   the HTTP request that led to the unauthorized access attempt
     * @param ResponseInterface              $response  the response object that will be modified to reflect the unauthorized status
     * @param UnauthorizedExceptionInterface $exception the exception triggered due to unauthorized access, containing details such as the error message
     *
     * @return ResponseInterface the modified response object with the unauthorized access error information, including a 401 status code and a JSON body with the error message and token information
     */
    public static function handleUnauthorized(ServerRequestInterface $request, ResponseInterface $response, UnauthorizedExceptionInterface $exception)
    {
        $output = [
            'message' => $exception->getMessage(),
            'token' => $request->getAttribute('authorization_token'),
            'success' => false,
        ];

        $response->getBody()->write(json_encode($output));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }

    /**
     * Verify if token is valid on database
     * If token isn't valid, expired or has insufficient scope must throw an UnauthorizedExceptionInterface.
     *
     * @param string $token Api Key
     *
     * @throws UnauthorizedExceptionInterface on invalid token
     *
     * @return array User object or associative array
     */
    abstract protected function getUserByToken(string $token);
}
