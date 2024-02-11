<?php

namespace Otodev\Validator;

use Otodev\Contracts\ValidatorContract;
use Otodev\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Validator;

/**
 * Class RepositoryValidator
 * @package Otodev\Validator
 */
class RepositoryValidator implements ValidatorContract
{
    /**
     * @var \Illuminate\Contracts\Validation\Validator
     */
    protected $validator;
    /**
     * The data under validation.
     *
     * @var array
     */
    protected $data;
    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    protected $rules;

    /**
     * RepositoryValidator constructor.
     *
     * @param array $rules
     */
    public function __construct(array $rules = null)
    {
        $this->rules = $rules;
    }

    /**
     * Set the validation data.
     *
     * @param array $data
     *
     * @return $this
     */
    public function with(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set the validation rules.
     *
     * @param array $rules
     *
     * @return $this
     */
    public function rules(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Pass the data and the rules to the validator or throws ValidationException
     *
     * @param string $action
     *
     * @return boolean
     * @throws ValidatorException
     */
    public function passesOrFail($action = null)
    {
        if (!$this->passes($action)) {
            throw new ValidatorException($this->validator);
        }

        return true;
    }

    /**
     * Pass the data and the rules to the validator.
     *
     * @param string|null $action
     *
     * @return bool
     */
    public function passes($action = null)
    {
        $this->validator = Validator::make($this->data, $this->getRules($action));

        if ($this->validator->fails()) {
            $this->errors = $this->validator->messages();

            return false;
        }

        return true;
    }

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
    public function getRules($action = null)
    {
        if (!isset($this->rules[$action])) {
            return $this->rules[ValidatorContract::RULE_CREATE];
        }

        return $this->rules[$action];
    }
}
