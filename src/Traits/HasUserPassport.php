<?php

namespace Otodev\Traits;

use App\Models\Auth\Permissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Otodev\Enums\RolesEnum;

trait HasUserPassport
{

    /**
     * Determine if the user has super admin privileges.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return ($this->company_id == RolesEnum::SUPER_ADMINS)
            && ($this->role->company_id == RolesEnum::SUPER_ADMINS)
            && $this->role->admin;
    }

    /**
     * Find the user instance for the given username.
     *
     * @param string $username
     *
     * @return mixed
     */
    public function findForPassport(string $username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return trim(ucfirst($this->first_name) . ' ' . ucfirst($this->last_name));
    }

    /**
     * Get the full name of the user trimmed.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return ucfirst($this->first_name) . ' ' . Str::upper(Str::substr($this->last_name, 0, 1)) . '.';
    }

    /**
     * Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role()
    {
        return $this->hasOne('App\Models\Auth\Roles', 'id', 'role_id');
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $credentials
     * @param bool $withCompany
     *
     * @return false|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function scopeRetrieveByCredentials(Builder $query, array $credentials, bool $withCompany = false)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                Str::contains($this->firstCredentialKey($credentials), 'password'))) {
            return false;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent Admins "model" that will be utilized by the Guard instances.
        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value) {
            if (Str::contains($key, 'password')) {
                continue;
            }

            if (is_array($value) || $value instanceof Arrayable) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }

        return $this->getUserLogin($query, $withCompany);
    }

    /**
     * Get the first key from the credential array.
     *
     * @param array $credentials
     *
     * @return int|string
     */
    public function firstCredentialKey(array $credentials)
    {
        foreach ($credentials as $key => $value) {
            return $key;
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $withCompany
     *
     * @return false|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    protected function getUserLogin(Builder $query, bool $withCompany = false)
    {
        $relations = ['role'];

        if ($withCompany) {
            $relations[] = 'company';

            $query->whereHas('company', function ($query) {
                $query->whereActive(1);
            });
        }

        // Now we are ready to execute the query to see if we have an user matching
        // the given credentials. If not, we will just return nulls and indicate
        // that there are no matching users for these given credential arrays.
        $user = $query->with($relations)->first();

        if ($user) {
            $role = $user->getRelation('role');

            if ($role) {
                $admin = $role->admin;
                $all_permissions = Permissions::getAllByPermissions($role->permissions, $user);
                $permissions = optional($all_permissions)->permissions ?? [];
                $scopes = optional($all_permissions)->scopes ?? [];
                $scope_ids = optional($all_permissions)->ids ?? [];

                // Set extra atributes
                $user->setAttribute('admin', $admin);
                $user->setAttribute('permissions', $permissions);
                $user->setAttribute('scopes', $scopes);
                $user->setAttribute('scope_ids', $scope_ids);
                $user->setAttribute('role_name', $role->name);

                // Unset role relation
                $user->unsetRelation('role');

                return $user;
            }
        }

        return false;
    }

    /**
     * Retrieve a user by user id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param                                       $user_id
     * @param bool $withCompany
     *
     * @return false|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function scopeRetrieveById(Builder $query, $user_id, bool $withCompany = false)
    {
        $query->whereKey($user_id);

        return $this->getUserLogin($query, $withCompany);
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $credentials
     * @param bool $withCompany
     *
     * @return bool
     */
    public function scopeAttempt(Builder $query, array $credentials = [], bool $withCompany = false): bool
    {
        $user = $query->retrieveByCredentials($credentials, $withCompany);

        if (!$user) {
            return false;
        }

        // If an implementation of UserInterface was returned, we'll ask the provider
        // to validate the user against the given credentials, and if they are in
        // fact valid we'll log the users into the application and return true.
        if ($this->hasValidCredentials($user, $credentials)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param       $user
     * @param array $credentials
     *
     * @return bool
     */
    public function hasValidCredentials($user, array $credentials): bool
    {
        $validated = !is_null($user) && $this->validateCredentials($user, $credentials);

        return $validated;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array $credentials
     *
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $plain = $credentials['password'];

        return Hash::check($plain, $user->getAuthPassword());
    }


}
