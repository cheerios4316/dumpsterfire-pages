<?php

namespace DumpsterfirePages\Validator;

use DumpsterfirePages\Validator\StringValidator;

class StringNotWhitespaceValidator extends StringNotEmptyValidator
{
    public function performValidation($value): bool
    {
        return parent::performValidation($value) && !empty(trim((string)$value));
    }
}