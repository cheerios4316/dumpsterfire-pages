<?php

namespace DumpsterfirePages\InitActions;

use Dotenv\Dotenv;
use DumpsterfirePages\Interfaces\InitActionInterface;

class DotEnvInit implements InitActionInterface
{
    private static bool $ran = false;
    public function run(): void
    {
        if (!self::$ran) {
            $dotenv = Dotenv::createImmutable('/var/www/html');
            $dotenv->load();

            self::$ran = true;
        }
    }
}