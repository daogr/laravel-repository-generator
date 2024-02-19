<?php

use Illuminate\Contracts\Foundation\Application;

if (! function_exists('core')) {
    /**
     * Returns the instance of Core Class
     *
     * @return Application|mixed
     */
    function core(): mixed
    {
        return app('core');
    }
}

if (! function_exists('is_skipped_resource')) {
    /**
     * Retrieve the value for the skipped resource.
     */
    function is_skipped_resource(): bool
    {
        return app('request')->boolean(config('repository.resource.params.skipResource', 'skipResource'), false);
    }
}
