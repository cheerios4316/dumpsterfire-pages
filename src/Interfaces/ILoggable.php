<?php

namespace DumpsterfireBase\Interfaces;

use DumpsterfireBase\Interfaces\LoggerInterface;

interface ILoggable {
    public function setLogger(LoggerInterface $loggerInterface): self;
}