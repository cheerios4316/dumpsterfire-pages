<?php

namespace DumpsterfirePages\Exceptions;

use Exception;

// @todo add constraints (e.g. BetweenConstraint
class ConstraintException extends Exception
{
    public function __construct($val1, $val2, string $type = "", int $code = 0, \Throwable $previous = null)
    {
        $message = "Failed check of type '$type' on values $val1 and $val2";

        parent::__construct($message);
    }
}
