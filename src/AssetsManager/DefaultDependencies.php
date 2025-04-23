<?php

namespace DumpsterfirePages\AssetsManager;

use DumpsterfirePages\Interfaces\LoggerInterface;
use DumpsterfirePages\Exceptions\AssetsException;

class DefaultDependencies
{
    /**
     * @var array{'js': string[], 'css': string[]}
     */
    protected static array $default = [
        'js' => [
            '/public/dist/bundle.js',
        ],
        'css' => [
            '/public/dist/tailwind.css' //@todo refactor to use bundled 
        ]
    ];

    protected static ?LoggerInterface $logger = null;

    /**
     * @var string[] $allowedTypes
     */
    private static array $allowedTypes = ['js', 'css'];

    public static function get(): array
    {
        return self::$default;
    }

    /**
     * @param string $type
     * @return void
     * @throws AssetsException
     */
    protected static function protect(string $type): void
    {
        if (!in_array($type, self::$allowedTypes)) {
            throw new AssetsException("Invalid asset type: $type");
        }
    }
}