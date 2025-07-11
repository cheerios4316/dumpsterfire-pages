<?php

namespace DumpsterfirePages\Validator;

use DumpsterfirePages\Constants\ValidatorEnum;
use DumpsterfirePages\Exceptions\ValidatorException;
use DumpsterfirePages\Interfaces\Validator;

class StringValidator implements Validator
{
    public final function validate($value): string
    {
        if ($this->performValidation($value)) {
            return (string) $value;
        }

        $this->throw();
    }

    protected function performValidation($value): bool
    {
        return is_string($value);
    }

    protected final function throw(): never
    {
        throw new ValidatorException(ValidatorEnum::String);
    }
}