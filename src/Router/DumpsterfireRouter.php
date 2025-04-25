<?php

namespace DumpsterfirePages\Router;

use DumpsterfirePages\Container\Container;
use DumpsterfirePages\Controller\Page404Controller;
use DumpsterfirePages\Exceptions\ControllerException;
use DumpsterfirePages\Interfaces\LoggerInterface;
use DumpsterfirePages\Exceptions\RoutingException;
use DumpsterfirePages\Interfaces\ControllerInterface;
use DumpsterfirePages\Interfaces\IControllerParams;
use DumpsterfirePages\Interfaces\RouterInterface;
use DumpsterfirePages\PageComponent;
use Exception;
use DumpsterfirePages\Interfaces\ILoggable;
use Throwable;

class DumpsterfireRouter implements RouterInterface, ILoggable
{
    /**
     * @var RouterInterface[];
     */
    protected static array $routers = [];

    /**
     * Base path for all the routes defined in the router.
     *
     * @var string
     */
    protected string $prefix = "";

    protected ?LoggerInterface $logger = null;

    protected Container $container;

    protected ?PageComponent $page404Component = null;

    /**
     * 
     * @var array<string, ControllerInterface> $routes;
     */
    protected static array $routes = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $route
     * @return ControllerInterface
     * @throws ControllerException
     */
    public function getControllerFromRoute(string $route): ControllerInterface
    {
        try {
            return $this->matchRoute($route);
        } catch (Throwable $e) {
            $this->logger?->log($e->getMessage());
            return $this->get404controller();
        }
    }

    /**
     * @param string $path
     * @param string $controllerInterface
     * @param array $routes
     *
     * @return RouterInterface
     * @throws RoutingException
     */
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
        return [...self::$routes];
    }

    /**
     * @param string $route
     * @return ControllerInterface
     * @throws Exception
     */
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
                $controller = $this->container->create($controller);

                if($controller instanceof IControllerParams) {
                    $controller->setParams($matches);
                }

                return $controller;
            }
        }

        throw new Exception('Controller not found for route ' . $route);
    }

    /**
     * @return ControllerInterface
     * @throws ControllerException
     */
    public function get404Controller(): ControllerInterface
    {
        if(!$this->page404Component) {
            $message = 'Page404Component must be set in order to display a 404 page. Please use App::set404PageComponent.';
            $this->logger?->log($message);
            throw new ControllerException($message);
        }

        $controller = $this->container->create(Page404Controller::class);
        return $controller->set404Page($this->page404Component);
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

    public function set404PageComponent(PageComponent $page404Component): self
    {
        $this->page404Component = $page404Component;
        return $this;
    }
}