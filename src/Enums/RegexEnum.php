<?php

namespace DumpsterfirePages\Enums;

enum RegexEnum: string
{
    case Email = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
}