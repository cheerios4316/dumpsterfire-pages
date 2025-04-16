<?php

namespace DumpsterfirePages\Interfaces;

use DumpsterfirePages\Interfaces\LoggerInterface;

interface ILoggable {
    public function setLogger(LoggerInterface $loggerInterface): self;
}