<?php

namespace DumpsterfirePages\Validator;

use DumpsterfirePages\Validator\StringValidator;

class StringNotEmptyValidator extends StringValidator
{
    public function performValidation($value): bool
    {
        return parent::performValidation($value) && !empty((string)$value);
    }
}