<?php

namespace DumpsterfirePages\Validator;

use DumpsterfirePages\Container\Container;
use DumpsterfirePages\Interfaces\Validator;

final class Validate
{
    /**
     * @param mixed $value
     * @param class-string<Validator> $validator
     */
    public static function validate($value, string $validator)
    {
        $validator = Container::getInstance()->get($validator);

        return $validator->validate($value);
    }

    public static function int($value): int
    {
        return self::validate($value, IntValidator::class);
    }

    public static function float($value): float
    {
        return self::validate($value, FloatValidator::class);
    }

    public static function string($value): string
    {
        return self::validate($value, StringValidator::class);
    }

    public static function bool($value): bool
    {
        return self::validate($value, BoolValidator::class);
    }

    public static function stringNotEmpty($value): string
    {
        return self::validate($value, StringNotEmptyValidator::class);
    }

    public static function stringNotWhitespace($value): string
    {
        return self::validate($value, StringNotWhitespaceValidator::class);
    }

    public static function email($value): string
    {
        return self::validate($value, EmailValidator::class);
    }
}
