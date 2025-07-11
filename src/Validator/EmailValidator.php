<?php

namespace DumpsterfirePages\Validator;

use DumpsterfirePages\Constants\ValidatorEnum;
use DumpsterfirePages\Enums\RegexEnum;
use DumpsterfirePages\Exceptions\ValidatorException;
use DumpsterfirePages\Interfaces\Validator;

class EmailValidator implements Validator
{
    public function __construct(
        private StringValidator $stringValidator,
        private BoolValidator $boolValidator
        ) {}

    public function validate($value): string
    {
        try {
            $value = $this->stringValidator->validate($value);
        } catch (ValidatorException $e) {
            throw new ValidatorException(ValidatorEnum::Email);
        }

        $result = $this->matchEmailRegex($value);

        $isMail = $this->boolValidator->validate($result);

        if($isMail) {
            return $value;
        }

        throw new ValidatorException(ValidatorEnum::Email);
    }

    private function matchEmailRegex(string $value): int|false
    {
        $regex = RegexEnum::Email;
        return preg_match($regex->value, $value);
    }
}