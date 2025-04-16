<?php

namespace DumpsterfireComponents\AssetsManager;

use DumpsterfireBase\Interfaces\LoggerInterface;
use DumpsterfireComponents\Exceptions\AssetsException;

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

    /**
     * Add a logger to log errors
     * @todo move this into some class in DumpsterfireBase
     * @param LoggerInterface $logger
     * @return void
     */
    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    public static function getLogger(): ?LoggerInterface
    {
        return self::$logger;
    }

    public static function get(): array
    {
        return self::$default;
    }

    public static function addJs(string $path): void
    {
        self::add($path, 'js');
    }

    public static function addCss(string $path): void
    {
        self::add($path, 'css');
    }

    protected static function add(string $path, string $type): void
    {
        try {
            self::protect($type);
        } catch (AssetsException $e) {
            self::$logger?->log($e->getMessage());
            return;
        }
        if (!in_array($path, self::$default[$type])) {
            self::$default[$type][] = $path;
        }
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