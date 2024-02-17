<?php

namespace Otodev\Traits;

use Illuminate\Support\Collection as BaseCollection;

trait CastAttributeValue
{

    /**
     * Cast value to the specified type.
     *
     * @param $type
     * @param $value
     *
     * @return mixed
     */
    public function getValueAttributeByCastType($type, $value)
    {
        return $this->castAttributeValue($type, $value);
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function castAttributeValue($key, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        switch ($key) {
            case 'int':
            case 'integer':
                return (int)$value;
            case 'real':
            case 'float':
            case 'double':
                return $this->fromFloat($value);
            case 'decimal':
                return $this->asDecimal($value, explode(':', $this->getCasts()[$key], 2)[1]);
            case 'string':
                return (string)$value;
            case 'bool':
            case 'boolean':
                if (in_array($value, ["false", "0"])) {
                    return false;
                }

                return (bool)$value;
            case 'object':
                return $this->fromJson($value, true);
            case 'array':
            case 'json':
                return $this->fromJson($value);
            case 'collection':
                return new BaseCollection($this->fromJson($value));
            case 'date':
                return $this->asDate($value);
            case 'datetime':
            case 'custom_datetime':
                return $this->asDateTime($value);
            case 'timestamp':
                return $this->asTimestamp($value);
        }

        return $value;
    }
}
