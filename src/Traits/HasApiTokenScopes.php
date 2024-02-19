<?php

namespace Otodev\Traits;

use Illuminate\Support\Str;

trait HasApiTokenScopes
{

    /**
     * Determine if the token has a given scope.
     *
     * @param string $scope
     *
     * @return bool
     */
    public function canScope(string $scope): bool
    {
        if (Str::contains($scope, '.')) {
            list($s1, $s2) = explode('.', $scope);

            $scope = Str::upper($s1) . '.' . Str::lower($s2);
        }

        $user = $this->accessToken ? $this->accessToken->user : false;
        if (!empty($user)) {
            $scopes = $user['scopes'];

            return in_array('*', $scopes) ||
                array_key_exists($scope, array_flip($scopes));
        }

        return false;
    }
}
