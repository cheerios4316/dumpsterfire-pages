<?php

namespace DumpsterfirePages\Exceptions;

use DumpsterfirePages\Constants\ValidatorEnum;
use Exception;

class ValidatorException extends Exception
{
    public function __construct(ValidatorEnum $type)
    {
        $message = "Failed validation on type '" . $type->value . "'";

        parent::__construct($message);
    }
}