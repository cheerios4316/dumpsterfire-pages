<?php

namespace DumpsterfireBase\Container;
use DumpsterfireBase\Interfaces\LoggerInterface;
use DumpsterfireBase\Interfaces\SingletonInterface;
use ReflectionClass;
use DumpsterfireBase\Interfaces\ILoggable;

/**
 * @template T
 */
class Container implements SingletonInterface
{
    protected static ?Container $instance = null;

    protected DependencyResolver $dependencyResolver;

    protected static ?LoggerInterface $logger = null;

    private function __construct(DependencyResolver $dependencyResolver)
    {
        $this->dependencyResolver = $dependencyResolver;
    }

    /**
     * Creates a new instance of a class.
     *
     * @param class-string<T> $class
     * @return T|null
     */
    public function create(string $class)
    {
        if (is_subclass_of($class, SingletonInterface::class)) {
            return $class::getInstance();
        }

        try {
            $reflection = new ReflectionClass($class);

            $deps = $this->dependencyResolver->resolve($reflection);

            $instance = $reflection->newInstanceArgs($deps);

            if (is_subclass_of($class, ILoggable::class) && !is_null($logger = self::$logger)) {
                $instance->setLogger($logger);
            }

            return $instance;
        } catch (\Exception $e) {
            if(self::$logger) {
                self::$logger->log($e->getMessage());
            }
            return null;
        }
    }

    /**
     * Returns the singleton instance of the container
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self(new DependencyResolver());
        }

        return self::$instance;
    }

    public static function setLogger(LoggerInterface $loggerInterface): void
    {
        self::$logger = $loggerInterface;
    }
}