<?php

namespace DumpsterfirePages\InitActions;

use DumpsterfirePages\Interfaces\InitActionInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class WhoopsInit implements InitActionInterface
{
    public function run(): void
    {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
    }
}