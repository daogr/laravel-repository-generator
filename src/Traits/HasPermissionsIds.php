<?php

namespace Otodev\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasPermissionsIds
{

    /**
     * Returns the permission ids by role id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param                                       $role_id
     *
     * @return mixed
     */
    public function scopeGetPermissionIdsByRoleId(Builder $query, $role_id): mixed
    {
        $permissions = $query->whereKey($role_id)->get(['permissions']);

        return $permissions->map(function ($permission) {
            return collect($permission->permissions)->reject(function ($action) {
                return empty(collect($action)->reject(function ($action) {
                    return $action != "access";
                })->toArray());
            })->keys();
        })->first();
    }
}
