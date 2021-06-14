<?php

    namespace Otodev\Traits;

    use Illuminate\Support\Str;

    trait HasFixCriteriaKeys {

        /**
         * Fix request criteria keys.
         *
         * @param array $fields
         *
         * @return array
         */
        public function fixRequestKeys(array $fields): array {
            if(!is_skipped_resource()) {
                return collect($fields)->map(function($key) {
                    return Str::snake($key);
                })->toArray();
            }

            return $fields;
        }

        /**
         * Fix request criteria map keys.
         *
         * @param array $fields
         *
         * @return array
         */
        public function fixRequestMapKeys(array $fields): array {
            if(!is_skipped_resource()) {
                return collect($fields)->mapWithKeys(function($value, $key) {
                    return [Str::snake($key) => $value];

                })->reject(function($value) {
                    return is_null($value) || $value == '';

                })->toArray();
            }

            return $fields;
        }
    }
