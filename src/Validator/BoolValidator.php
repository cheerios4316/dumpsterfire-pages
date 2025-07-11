<?php

namespace DumpsterfirePages\Validator;

use DumpsterfirePages\Constants\ValidatorEnum;
use DumpsterfirePages\Exceptions\ValidatorException;
use DumpsterfirePages\Interfaces\Validator;

class BoolValidator implements Validator
{
    public function validate($value): bool
    {
        $result = match ($value) {
            true => true,
            false => false,
            1 => true,
            0 => false,
            "true" => true,
            "false" => false,
            "1" => true,
            "0" => false,
            default => null
        };

        if(is_null($result)) {
            throw new ValidatorException(ValidatorEnum::Bool);
        }

        return $result;
    }
}