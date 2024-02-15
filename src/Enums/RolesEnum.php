<?php

namespace Otodev\Enums;

use Illuminate\Support\Facades\Auth;

class RolesEnum
{

    /**
     * User Roles
     */
    const SUPER_ADMINS = 1;
    const ADMINS = 10;

    /**
     * Determine if the user has super admin privileges.
     *
     * @param null $user
     *
     * @return bool
     */
    public static function isSuperAdmin($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        return ($user->company_id == static::SUPER_ADMINS)
            && ($user->role->company_id == static::SUPER_ADMINS)
            && $user->role->admin;
    }

    /**
     * Determine if the user has admin privileges.
     *
     * @param null $user
     *
     * @return bool
     */
    public static function isAdmin($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        return $user->role->admin;
    }

}
