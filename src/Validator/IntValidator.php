<?php

namespace DumpsterfirePages\Validator;

use DumpsterfirePages\Constants\ValidatorEnum;
use DumpsterfirePages\Exceptions\ValidatorException;
use DumpsterfirePages\Interfaces\Validator;

class IntValidator implements Validator
{
    public function validate($value): int
    {
        if (preg_match('/^-?\d+$/', (string) $value)) {
            return (int) $value;
        }

        throw new ValidatorException(ValidatorEnum::Integer);
    }
}