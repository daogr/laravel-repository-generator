<?php
	
	namespace Otodev\Contracts\Models;
	
	use Illuminate\Contracts\Auth\Authenticatable;
	use Illuminate\Database\Eloquent\Builder;
	
	/**
	 * Interface UserPassportContract
	 * @package Otodev\Contracts\Models
	 */
	interface UserPassportContract {
		
		/**
		 * Determine if the token has a given scope.
		 *
		 * @param string $scope
		 *
		 * @return bool
		 */
		public function canScope(string $scope): bool;
		
		/**
		 * Find the user instance for the given username.
		 *
		 * @param string $username
		 *
		 * @return mixed
		 */
		public function findForPassport(string $username);
		
		/**
		 * Get the full name of the user.
		 *
		 * @return string
		 */
		public function getNameAttribute(): string;
		
		/**
		 * Role
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\HasOne
		 */
		public function role();
		
		/**
		 * Retrieve a user by the given credentials.
		 *
		 * @param \Illuminate\Database\Eloquent\Builder $query
		 * @param array                                 $credentials
		 * @param bool                                  $withCompany
		 *
		 * @return false|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
		 */
		public function scopeRetrieveByCredentials(Builder $query, array $credentials, bool $withCompany = false);
		
		/**
		 * Attempt to authenticate a user using the given credentials.
		 *
		 * @param \Illuminate\Database\Eloquent\Builder $query
		 * @param array                                 $credentials
		 * @param bool                                  $remember
		 *
		 * @return bool
		 */
		public function scopeAttempt(Builder $query, array $credentials = [], bool $remember = false): bool;
		
		/**
		 * Validate a user against the given credentials.
		 *
		 * @param \Illuminate\Contracts\Auth\Authenticatable $user
		 * @param array                                      $credentials
		 *
		 * @return bool
		 */
		public function validateCredentials(Authenticatable $user, array $credentials): bool;
		
		/**
		 * Get the first key from the credential array.
		 *
		 * @param array $credentials
		 *
		 * @return int|string
		 */
		public function firstCredentialKey(array $credentials);
		
		/**
		 * Determine if the user matches the credentials.
		 *
		 * @param       $user
		 * @param array $credentials
		 *
		 * @return bool
		 */
		public function hasValidCredentials($user, array $credentials): bool;
	}
