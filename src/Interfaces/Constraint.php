<?php

namespace DumpsterfirePages\Interfaces;

interface Constraint
{
    public function check($value1, $value2): bool;
}