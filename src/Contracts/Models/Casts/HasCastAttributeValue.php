<?php
	
	namespace Otodev\Contracts\Models\Casts;
	
	interface HasCastAttributeValue {
		
		/**
		 * Cast an attribute to a native PHP type.
		 *
		 * @param string $key
		 * @param mixed  $value
		 *
		 * @return mixed
		 */
		public function castAttributeValue($key, $value);
		
	}
