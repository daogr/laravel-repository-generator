<?php

namespace Otodev\Traits;

use Illuminate\Support\Str;

trait HasFixRuleKeys
{

    /**
     * Fix request rule keys.
     *
     * @param array $rules
     *
     * @return array
     */
    public function fixRequestRuleKeys(array $rules)
    {
        if (!is_skipped_resource()) {
            return collect($rules)->mapWithKeys(function ($rule, $key) {
                return [Str::studly($key) => $rule];
            })->toArray();
        }

        return $rules;
    }

    /**
     * Fix store rule keys.
     *
     * @param array $rules
     *
     * @return array
     */
    public function fixStoreRuleKeys(array $rules)
    {
        return collect($rules)->mapWithKeys(function ($rule, $key) {
            return [Str::snake($key) => $rule];
        })->toArray();
    }
}
