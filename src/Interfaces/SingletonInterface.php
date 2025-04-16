<?php

namespace DumpsterfirePages\Interfaces;

interface SingletonInterface
{
    public static function getInstance(): self;
}