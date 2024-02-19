<?php

namespace Otodev\Traits;

use Otodev\Contracts\ValidatorContract;

trait HasValidatorAction
{

    /**
     * Returns the validator action.
     *
     * @return string
     */
    public function getAction()
    {
        $id = $this['Id'] ?? $this['id'] ?? null;

        if (!empty($id) && is_numeric($id)) {
            return ValidatorContract::RULE_UPDATE;
        } else {
            return ValidatorContract::RULE_CREATE;
        }

    }

}
