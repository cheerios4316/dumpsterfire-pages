<?php

namespace DumpsterfirePages\Router;

use DumpsterfirePages\Container\Container;
use DumpsterfirePages\Exceptions\ContainerException;
use DumpsterfirePages\Interfaces\ControllerInterface;
use DumpsterfirePages\Interfaces\IControllerParams;
use DumpsterfirePages\Interfaces\SingletonInterface;
use ReflectionException;

class RoutesMatcher implements SingletonInterface
{
    protected static ?self $instance = null;

    public function __construct(protected Container $container) {}

    /**
     * @param string $route
     * @param array $routeList
     * @param string $prefix
     * @return ControllerInterface
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function match(string $route, array $routeList, string $prefix = ""): ControllerInterface
    {
        /**
         * @var class-string<ControllerInterface> $controller
         */
        foreach ($routeList as $path => $controller) {
            $path =  $this->getPath($path, $prefix);

            $pattern = $this->getPattern($path);

            preg_match($pattern, $route, $matches);

            if (!empty($matches) && is_array($matches) && !empty($matches[0])) {
                $controller = $this->container->create($controller);

                if($controller instanceof IControllerParams) {
                    $controller->setParams($matches);
                }

                return $controller;
            }
        }

        throw new ContainerException('Controller not found for route ' . $route);
    }

    protected function getPattern(string $path): string
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . str_replace('\\/', '/', $pattern) . '\/?$#';
    }

    protected function getPath(string $path, string $prefix = ""): string
    {
        return '/' . $prefix . trim($path, '/');
    }

    public static function getInstance(): self
    {
        if(!self::$instance) {
            self::$instance = new self(Container::getInstance());
        }
        return self::$instance;
    }
}