<?php

namespace Otodev\Requests;

use Otodev\Contracts\ApiRequestContract;
use Otodev\Traits\HasFixRuleKeys;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ApiRequest
 * @package Otodev\Requests
 */
class ApiRequest extends FormRequest implements ApiRequestContract
{
    use HasFixRuleKeys;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
