<?php

namespace Otodev\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait HasPermissionsScopes
{

    /**
     * Returns all permissions, scopes, and ids.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param                                       $user_permissions
     *
     * @return object
     */
    public function scopeGetAllByPermissions(Builder $query, $user_permissions): object
    {
        $permissions = $query->get(['id', 'name']);

        $scopes = (object)[
            'permissions' => [],
            'scopes' => [],
            'ids' => []
        ];

        foreach ($permissions as $permission) {
            foreach ($user_permissions as $id => $user_permission) {
                if ($permission->id == $id) {
                    $scopes->ids[] = $id;
                    foreach ($user_permission as $user_permission_) {
                        $scopes->permissions[Str::upper($permission->name)][] = Str::lower($user_permission_);
                        $scopes->scopes[] = Str::upper($permission->name) . '.' . Str::lower($user_permission_);
                    }
                }
            }
        }

        return $scopes;
    }
}
