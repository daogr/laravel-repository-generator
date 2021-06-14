<?php

	namespace Otodev\Core;

	/**
	 * Class Core
	 * @package Otodev\Core
	 */
	class Core {

		/**
		 * Returns all locales from database.
		 *
		 * @return mixed
		 */
		public function getLocales() {
			static $locales;

			if($locales) {
				return $locales;
			}

			//return $locales = $this->localeRepository->all();

			return $locales = [
				(object) ['code' => 'el'],
				(object) ['code' => 'en'],
			];
		}


		/**
		 * Returns all locale codes.
		 *
		 * @return array
		 */
		public function locales() {
			static $locales = [];

			if($locales) {
				return $locales;
			}

			foreach(static::getLocales() as $locale) {
				$locales[$locale->code] = $locale->code;
			}

			return $locales;
		}

	}
