<?php

namespace DumpsterfirePages\Interfaces;

interface LoggerInterface
{
    public function log(string $message): self;
}