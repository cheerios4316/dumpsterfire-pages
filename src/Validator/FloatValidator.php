<?php

namespace DumpsterfirePages\Validator;

use DumpsterfirePages\Constants\ValidatorEnum;
use DumpsterfirePages\Exceptions\ValidatorException;
use DumpsterfirePages\Interfaces\Validator;

class FloatValidator implements Validator
{
    public function validate($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        throw new ValidatorException(ValidatorEnum::Float);
    }
}