<?php

	namespace Otodev\Traits;

	use Illuminate\Support\Str;

	trait UseResourceFillable {

		/**
		 * Transform the resource into an array.
		 *
		 * @return array
		 */
		public function transformed() {
			return $this->fillable();
		}

		/**
		 * Transform with fillable array.
		 *
		 * @return array
		 */
		protected function fillable() {
			$fillable = $this->fillable ?? [];

			return collect($fillable)->mapWithKeys(function($key) {
				if(is_skipped_resource()) {
					if(isset($this->{$key})) {
						return [$key => $this->{$key}];
					}
				} else {
					if(isset($this->{$key})) {
						return [Str::studly($key) => $this->{$key}];
					}
				}

				return [];
			})->toArray();
		}
	}
