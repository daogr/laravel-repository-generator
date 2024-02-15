<?php

namespace Otodev\Traits;

use Exception;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\RegisteredClaims;

trait HasValidateToken
{

    /**
     * Check if the requested token is valid
     *
     * https://symfony.com/doc/current/components/psr7.html
     * composer require nyholm/psr7
     *
     * @param Request $request
     * @param bool $localCall
     *
     * @return Token|bool|false|string
     * @throws Exception
     */
    protected function validateToken(Request $request, $localCall = false)
    {
        try {
            $jwt = $request->bearerToken();

            if (!empty($jwt)) {
                // https://lcobucci-jwt.readthedocs.io/en/latest/validating-tokens/#using-lcobuccijwtvalidatorvalidate
                // https://stackoverflow.com/questions/65014655/laravel-target-lcobucci-jwt-parser-is-not-instantiable

                $token = (new Parser(new JoseEncoder()))->parse($jwt);
                $token_id = $token->claims()->get(RegisteredClaims::ID);
                $access_token = TokenModel::find($token_id);

                if ($access_token) {
                    /** Check token's expiration */
                    $authenticated = $access_token->expires_at->gt(now());

                    if ($localCall) {
                        return $authenticated ? $access_token : false;
                    } else {
                        return json_encode(array('authenticated' => $authenticated));
                    }
                }
            }
        } catch (Exception $e) {
        }

        if ($localCall) {
            return false;
        } else {
            return json_encode(array('error' => 'Something went wrong with authenticating. Please logout and login again.'));
        }
    }
}
