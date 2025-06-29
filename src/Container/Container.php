<?php

namespace DumpsterfirePages\Container;
use DumpsterfirePages\Interfaces\ContainerInterface;
use DumpsterfirePages\Interfaces\LoggerInterface;
use DumpsterfirePages\Interfaces\SingletonInterface;
use ReflectionClass;
use DumpsterfirePages\Interfaces\ILoggable;

/**
 * @template T
 */
class Container implements SingletonInterface, ContainerInterface
{
    protected static ?Container $instance = null;

    protected static bool $suppress = false;

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
            if(self::$suppress) {
                return null;
            }

            throw $e;
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

    public static function setSuppress(bool $suppress): void
    {
        self::$suppress = $suppress;
    }
}