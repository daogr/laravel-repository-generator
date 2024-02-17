<?php

namespace Otodev\Contracts;

use Illuminate\Validation\ValidationException;

/**
 * Interface ValidatorContract
 * @package Otodev\Contracts
 */
interface ValidatorContract
{
    /**
     * Create rule constant
     */
    const RULE_CREATE = 'create';
    /**
     * Update rule constant
     */
    const RULE_UPDATE = 'update';

    /**
     * Set the validation data.
     *
     * @param array $data
     *
     * @return $this
     */
    public function with(array $data);

    /**
     * Set the validation rules.
     *
     * @param array $rules
     *
     * @return $this
     */
    public function rules(array $rules);

    /**
     * Pass the data and the rules to the validator.
     *
     * @param string|null $action
     *
     * @return bool
     */
    public function passes($action = null);

    /**
     * Pass the data and the rules to the validator or throws ValidationException
     *
     * @param string $action
     *
     * @return boolean
     * @throws ValidationException
     */
    public function passesOrFail($action = null);

    /**
     * Get the validation rules by action
     *
     * ValidatorContract::RULE_CREATE -> default
     * ValidatorContract::RULE_UPDATE
     *
     * @param null|string $action
     *
     * @return array
     */
    public function getRules($action = null);
}
