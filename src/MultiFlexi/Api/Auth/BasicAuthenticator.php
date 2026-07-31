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

namespace MultiFlexi\Api\Auth;

/**
 * Description of ApiKeyAuthenticator.
 *
 * @author vitex
 *
 * @no-named-arguments
 */
class BasicAuthenticator extends Authenticator
{
    public function __construct($requiredScope = null)
    {
        parent::__construct($requiredScope);
    }

    public function __invoke(\Psr\Http\Message\ServerRequestInterface &$request, \Dyorg\TokenAuthentication\TokenSearch $tokenSearch)
    {
        $prober = \Ease\Shared::user(null, '\MultiFlexi\User');

        if ($prober->isLogged()) {
            return true;
        }

        // $tokenSearch captures the base64 payload after "Basic " from the
        // Authorization header (see the regex in RegisterRoutes.php); the
        // request URI never carries these credentials, unlike a literal
        // http://user:pass@host URL, so it must not be read from getUserInfo().
        $userInfo = base64_decode($tokenSearch->getToken($request), true);

        if ($userInfo && strstr($userInfo, ':')) {
            [$login, $password] = explode(':', $userInfo, 2);
        } else {
            $login = $userInfo ?: '';
            $password = '';
        }

        $prober = new \MultiFlexi\User();
        $prober->loadFromSQL([$prober->loginColumn => $login]);

        return $prober->getUserID() && \strlen($password) && $prober->isAccountEnabled() && $prober->passwordValidation($password, $prober->getDataValue($prober->passwordColumn));
    }
}
