<?php

namespace DumpsterfirePages\Container;
use DumpsterfirePages\Exceptions\ContainerException;
use DumpsterfirePages\Interfaces\ContainerInterface;
use DumpsterfirePages\Interfaces\LoggerInterface;
use DumpsterfirePages\Interfaces\SingletonInterface;
use Exception;
use ReflectionClass;
use DumpsterfirePages\Interfaces\ILoggable;
use ReflectionException;

/**
 * @template T
 */
class Container implements SingletonInterface, ContainerInterface
{
    protected static ?Container $instance = null;

    protected static bool $suppress = false;

    protected DependencyResolver $dependencyResolver;

    protected static ?LoggerInterface $logger = null;

    /** @var array<class-string, object> $instances */
    protected array $instances = [];

    private function __construct(DependencyResolver $dependencyResolver)
    {
        $this->dependencyResolver = $dependencyResolver;
    }

    /**
     * Creates a new instance of a class.
     *
     * @param class-string<T> $class
     * @return T|null
     * @throws ContainerException
     * @throws ReflectionException
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
        } catch (Exception $e) {
            self::$logger?->log($e->getMessage());

            if(self::$suppress) {
                return null;
            }

            throw $e;
        }
    }

    /**
     * Gets a cached instance of a class
     *
     * @param class-string<T> $class
     * @param bool $new
     * @param bool $overwriteExisting
     * @return T|null
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function get(string $class, bool $new = false, bool $overwriteExisting = false)
    {
        if($new) {
            $instance = $this->create($class);

            if($overwriteExisting) {
                $this->instances[$class] = $instance;
            }

            return $instance;
        }

        if(!$this->has($class)) {
            $this->instances[$class] = $this->create($class);
        }

        return $this->instances[$class];
    }

    /**
     * Returns true if the container has a cached instance of the class
     * 
     * @param class-string $class
     * @return bool
     */
    public function has(string $class): bool
    {
        return isset($this->instances[$class]);
    }

    /**
     * If present, removes the cached instance from memory
     * 
     * @param class-string $class
     * @return self
     */
    public function remove(string $class): self
    {
        if($this->has($class)) {
            unset($this->instances[$class]);
        }

        return $this;
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