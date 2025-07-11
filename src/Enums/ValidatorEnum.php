<?php

namespace DumpsterfirePages\Constants;

enum ValidatorEnum: string
{
    case Integer = "Integer";
    case Float = "Float";
    case String = "String";
    case Bool = "Bool";
    case Email = "Email";
}