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
				if(isset($this->{$key})) {
					if(is_skipped_resource()) {
						return [$key => $this->{$key}];
					} else {
						return [Str::studly($key) => $this->{$key}];
					}
				}
				
				return [];
			})->toArray();
		}
	}
