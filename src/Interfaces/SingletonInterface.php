<?php

namespace DumpsterfireBase\Interfaces;

interface SingletonInterface
{
    public static function getInstance(): self;
}