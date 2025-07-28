<?php

namespace DumpsterfirePages\App\AppConfig;

use DumpsterfirePages\InitActions\DotEnvInit;
use DumpsterfirePages\InitActions\WhoopsInit;

class DefaultConfig extends AppConfig
{
    protected array $initActions = [
        DotEnvInit::class,
        WhoopsInit::class,
    ];
}