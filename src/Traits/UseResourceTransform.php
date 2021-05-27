<?php

    namespace Otodev\Traits;

    use Illuminate\Support\Str;

    trait UseResourceTransform {

        /**
         * Transform the resource into an array.
         *
         * @return array
         */
        public function transformed() {
            return array_merge($this->fillable(), $this->translations());
        }

        /**
         * Transform with fillable array.
         *
         * @return array
         */
        protected function fillable() {
            $fillable = $this->additional['fillable'] ?? [];

            return collect($fillable)->mapWithKeys(function($key) {
                if(is_skipped_resource()) {
					$value = $this[$key] ?? null;
					return [$key => $value];
					
                } else {
					$value = $this[Str::studly($key)] ?? null;
					return [$key => $value];
                }
            })->toArray();
        }

        /**
         * Transforms the translations
         *
         * @return array
         */
        protected function translations() {
            $locales = core()->locales();

            return collect($this->resource)->mapWithKeys(function($value, $key) use ($locales) {
                if(in_array($key, $locales)) {
                    if(is_array($value)) {
                        return [
                            $key => collect($value)->mapWithKeys(function($value, $key) {
                                if(is_array($value)) {
                                    return [Str::snake($key) => collect($value)->mapWithKeys(function($value, $key) { return [Str::snake($key) => $value]; })->toArray()];
                                } else {
                                    return [Str::snake($key) => $value];
                                }
                            })->toArray()
                        ];
                    } else {
                        return [Str::snake($key) => $value];
                    }
                }

                return [];
            })->toArray();
        }
    }
