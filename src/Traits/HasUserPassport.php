<?php

	namespace Otodev\Traits;

	use App\Models\Permissions;
	use Illuminate\Contracts\Support\Arrayable;
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Str;

	trait HasUserPassport {

		/**
		 * Find the user instance for the given username.
		 *
		 * @param string $username
		 *
		 * @return mixed
		 */
		public function findForPassport(string $username) {
			return $this->where('username', $username)->first();
		}

		/**
		 * Get the full name of the user.
		 *
		 * @return string
		 */
		public function getNameAttribute() {
			return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
		}

		/**
		 * Role
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\HasOne
		 */
		public function role() {
			return $this->hasOne('App\Models\Auth\Roles', 'id', 'role_id');
		}

		/**
		 * Retrieve a user by the given credentials.
		 *
		 * @param \Illuminate\Database\Eloquent\Builder $query
		 * @param array                                 $credentials
		 *
		 * @return false|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
		 */
		public function scopeRetrieveByCredentials(Builder $query, array $credentials) {
			if(empty($credentials) ||
				(count($credentials) === 1 &&
					Str::contains($this->firstCredentialKey($credentials), 'password'))) {
				return false;
			}

			// First we will add each credential element to the query as a where clause.
			// Then we can execute the query and, if we found a user, return it in a
			// Eloquent Admins "model" that will be utilized by the Guard instances.
			// $query = $this->newModelQuery();

			foreach($credentials as $key => $value) {
				if(Str::contains($key, 'password')) {
					continue;
				}

				if(is_array($value) || $value instanceof Arrayable) {
					$query->whereIn($key, $value);
				} else {
					$query->where($key, $value);
				}
			}

			// Now we are ready to execute the query to see if we have an user matching
			// the given credentials. If not, we will just return nulls and indicate
			// that there are no matching users for these given credential arrays.
			$user = $query->with('role')->first();
			/**
			 * Set the use full name
			 */
			$user->setAttribute('name', $this->getAttribute('name'));
			/**
			 * User Group
			 */
			$role = $user->getRelation('role');
			if($role) {
				$admin           = boolval($role->admin ?? false);
				$all_permissions = Permissions::getAllByPermissions($role->permissions, $user);
				$permissions     = optional($all_permissions)->permissions ?? [];
				$scopes          = optional($all_permissions)->scopes ?? [];
				$scope_ids       = optional($all_permissions)->ids ?? [];

				$user->setAttribute('admin', $admin);
				$user->setAttribute('permissions', $permissions);
				$user->setAttribute('scopes', $scopes);
				$user->setAttribute('scope_ids', $scope_ids);
				$user->setAttribute('role_name', $role->name);
				$user->unsetRelation('role');
			}

			return $user;
		}

		/**
		 * Attempt to authenticate a user using the given credentials.
		 *
		 * @param \Illuminate\Database\Eloquent\Builder $query
		 * @param array                                 $credentials
		 * @param bool                                  $remember
		 *
		 * @return bool
		 */
		public function scopeAttempt(Builder $query, array $credentials = [], bool $remember = false) {
			$user = $query->retrieveByCredentials($credentials);

			// If an implementation of UserInterface was returned, we'll ask the provider
			// to validate the user against the given credentials, and if they are in
			// fact valid we'll log the users into the application and return true.
			if($this->hasValidCredentials($user, $credentials)) {
				return true;
			}

			return false;
		}

		/**
		 * Validate a user against the given credentials.
		 *
		 * @param \Illuminate\Contracts\Auth\Authenticatable $user
		 * @param array                                      $credentials
		 *
		 * @return bool
		 */
		public function validateCredentials(\Illuminate\Contracts\Auth\Authenticatable $user, array $credentials) {
			$plain = $credentials['password'];

			return Hash::check($plain, $user->getAuthPassword());
		}

		/**
		 * Get the first key from the credential array.
		 *
		 * @param array $credentials
		 *
		 * @return int|string
		 */
		public function firstCredentialKey(array $credentials) {
			foreach($credentials as $key => $value) {
				return $key;
			}
		}

		/**
		 * Determine if the user matches the credentials.
		 *
		 * @param       $user
		 * @param array $credentials
		 *
		 * @return bool
		 */
		public function hasValidCredentials($user, array $credentials) {
			$validated = !is_null($user) && $this->validateCredentials($user, $credentials);

			return $validated;
		}

	}
