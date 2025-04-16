<?php

namespace DumpsterfirePages\Router;

use DumpsterfireBase\Container\Container;
use DumpsterfireBase\Interfaces\LoggerInterface;
use DumpsterfirePages\Exceptions\RoutingException;
use DumpsterfirePages\Interfaces\ControllerInterface;
use DumpsterfirePages\Interfaces\IControllerParams;
use DumpsterfirePages\Interfaces\RouterInterface;
use Exception;
use DumpsterfireBase\Interfaces\ILoggable;
use Throwable;

class DumpsterfireRouter implements RouterInterface, ILoggable
{
    /**
     * @var RouterInterface[];
     */
    protected static array $routers = [];

    protected string $prefix = "";

    protected ?LoggerInterface $logger = null;

    /**
     * 
     * @var array<string, ControllerInterface> $routes;
     */
    protected static array $routes = [];

    public function getControllerFromRoute(string $route): ControllerInterface
    {
        try {
            $controller = $this->matchRoute($route);
        } catch (Throwable $e) {
            $this->logger?->log($e->getMessage());
            $this->show404();
        }

        return $controller;
    }

    public function registerRoute(string $path, string $controllerInterface, array &$routes = []): RouterInterface
    {
        if (empty($routes)) {
            $routes = &self::$routes;
        }

        if (isset($routes[$path])) {
            throw new RoutingException('Routing rule "' . $path . '" is already defined.');
        }

        $routes[$path] = $controllerInterface;

        return $this;
    }

    public function addRouter(RouterInterface $router): self
    {
        self::$routers[$router::class] = $router;
        return $this;
    }

    public function setLogger(LoggerInterface $loggerInterface): self
    {
        $this->logger = $loggerInterface;
        return $this;
    }

    public function getRouters(): array
    {
        return self::$routers;
    }

    public function getRoutes(): array
    {
        $routes = [...self::$routes];

        return $routes;
    }

    protected function matchRoute(string $route): ControllerInterface
    {
        $routes = $this->getRoutes();

        /**
         * @var class-string<ControllerInterface> $controller
         */
        foreach ($routes as $path => $controller) {
            $path =  '/' . $this->prefix . trim($path, '/');
            
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
            $pattern = '#^' . str_replace('\\/', '/', $pattern) . '\/?$#';

            preg_match($pattern, $route, $matches);

            if (!empty($matches) && is_array($matches) && !empty($matches[0])) {
                $controller = Container::getInstance()->create($controller);

                if($controller instanceof IControllerParams) {
                    $controller->setParams($matches);
                }

                return $controller;
            }
        }

        throw new Exception('Controller not found for route ' . $route);
    }

    public function show404(): void
    {
        die('implement 404 page');
    }

    public function setPrefix(string $prefix): self
    {
        $this->prefix = empty($prefix) ?
            $prefix :
            trim($prefix, '/') . '/'
        ;

        return $this;
    }

    public static function new(): self
    {
        return Container::getInstance()->create(self::class);
    }
}