<?php

namespace DumpsterfireBase\Interfaces;

interface LoggerInterface
{
    public function log(string $message): self;
}