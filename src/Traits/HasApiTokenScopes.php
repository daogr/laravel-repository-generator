<?php

	namespace Otodev\Traits;

	trait HasApiTokenScopes {

		/**
		 * Determine if the token has a given scope.
		 *
		 * @param string $scope
		 *
		 * @return bool
		 */
		public function canScope(string $scope) {
			$user = $this->accessToken ? $this->accessToken->user : false;
			if(!empty($user)) {
				$scopes = $user['scopes'];

				return in_array('*', $scopes) ||
					array_key_exists($scope, array_flip($scopes));
			}

			return false;
		}
	}
