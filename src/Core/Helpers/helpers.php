<?php

	use Illuminate\Contracts\Foundation\Application;

	if(!function_exists('core')) {
		/**
		 * Returns the instance of Core Class
		 *
		 * @return Application|mixed
		 */
		function core() {
			return app('core');
		}
	}

	if(!function_exists('is_skipped_resource')) {
		/**
		 * Retrieve the value for the skipped resource.
		 *
		 * @return boolean
		 */
		function is_skipped_resource() {
			return app('request')->boolean(config('repository.resource.params.skipResource', 'skipResource'), false);
		}
	}

?>
