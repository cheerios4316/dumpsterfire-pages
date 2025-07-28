<?php

namespace DumpsterfirePages\App;

use DumpsterfirePages\Container\Container;
use DumpsterfirePages\Database\Connection;
use DumpsterfirePages\Database\DatabaseConnection;
use DumpsterfirePages\InitActions\DotEnvInit;
use DumpsterfirePages\InitActions\WhoopsInit;
use DumpsterfirePages\Interfaces\LoggerInterface;
use DumpsterfirePages\Component;
use DumpsterfirePages\PageComponent;
use DumpsterfirePages\PageTemplate\PageTemplate;
use DumpsterfirePages\Interfaces\RouterInterface;
use DumpsterfirePages\Interfaces\InitActionInterface;
use DumpsterfirePages\Interfaces\ILoggable;
use DumpsterfirePages\RequestManager\RequestHandles\SecureOnlyRequestHandle;
use DumpsterfirePages\RequestManager\RequestManager;
use DumpsterfirePages\Router\DumpsterfireRouter;
use Symfony\Component\HttpFoundation\Request;

class App implements ILoggable
{
    /** @var class-string<InitActionInterface>[] $initActions */
    protected array $initActions = [];

    /** @var class-string<InitActionInterface>[] $defaultInitActions */
    protected array $defaultInitActions = [
        DotEnvInit::class,
        WhoopsInit::class,
    ];

    protected ?PageComponent $page404Component = null;

    /** @var RouterInterface|null  */
    protected ?RouterInterface $router = null;

    protected bool $enforceHTTPS = true;

    /**
     * Sets the main router for your application. \
     * Create a router with: \
     * `$router = DumpsterfireRouter::new();` \
     * Then add routes with: \
     * `$router->registerRoute('/some/path', SomeController::class);`
     * 
     * @param RouterInterface $routerInterface
     * @return App
     */
    public function setRouter(RouterInterface $routerInterface): self
    {
        $this->router = $routerInterface;

        if($this->page404Component && $this->router instanceof DumpsterfireRouter) {
            $this->router->set404PageComponent($this->page404Component);
        }

        return $this;
    }

    /**
     * Runs your application.
     * @return App
     */
    public function run(): self
    {
        $request = $this->handleRequest();

        $requestUri = $request->getPathInfo();

        if($this->router) {
            $controller = $this->router->getControllerFromRoute($requestUri);
            $controller->getResult()->render();
        }

        return $this;
    }

    protected function handleRequest(): Request
    {
        $handlers = [];

        if($this->enforceHTTPS) {
            $handlers[] = SecureOnlyRequestHandle::class;
        }

        $request = RequestManager::Obtain(...$handlers)->handleRequest()->getRequest();

        return $request;
    }

    /**
     * Connects your application to a database.
     * @param string $host Hostname
     * @param string $dbname Database name
     * @param int $port Database port
     * @param string $username Database username
     * @param string $password Database password
     * @return App
     */
    public function connectDatabase(string $host, string $dbname, int $port, string $username, string $password): self
    {
        $container = Container::getInstance();
        $connection = $container->create(Connection::class);

        /** @var Connection $connection */
        $connection->connect($host, $dbname, $port, $username, $password);

        DatabaseConnection::setConnection($connection);
        
        return $this;
    }

    /**
     * Enables redirection to HTTPS for every request.
     * @return App
     */
    public function enforceHTTPS(): self
    {
        $this->enforceHTTPS = true;

        return $this;
    }

    /**
     * Will be deprecated
     * @return App
     */
    public function runInitActions(): self
    {
        foreach($this->initActions as $action) {

            $action = Container::getInstance()->create($action);
            $action->run();
        }

        return $this;
    }

    /**
     * Sets a logger for your application.
     * The logger is called automatically by the framework classes that implement ILoggable
     * @param LoggerInterface $loggerInterface
     * @return $this
     */
    public function setLogger(LoggerInterface $loggerInterface): self
    {
        Container::setLogger($loggerInterface);
        return $this;
    }

    /**
     * Sets a page component to display as the 404 page.
     * Upcoming: this is going to be deprecated in the routing refactor in favour of a controller.
     * @param \DumpsterfirePages\PageComponent $pageComponent
     * @return App
     */
    public function set404PageComponent(PageComponent $pageComponent): self
    {
        $this->page404Component = $pageComponent;

        return $this;
    }

    /**
     * Sets a header that is going to be rendered in each page.
     * @param class-string<Component> $component
     * @return App
     */
    public function setPageTemplateHeader(string $component): self
    {
        PageTemplate::setHeader($component);
        return $this;
    }

    /**
     * Sets a footer that is going to be rendered in each page.
     * @param class-string<Component> $component
     * @return App
     */
    public function setPageTemplateFooter(string $component): self
    {
        PageTemplate::setFooter($component);
        return $this;
    }

    /**
     * Returns the list of the init actions.
     * This is going to be lost in the App Config update.
     * @return class-string<InitActionInterface>[]
     */
    public function getInitActions(): array
    {
        return $this->initActions;
    }

    /**
     * Sets the init actions for the app. \
     * If you create the app via `::new()`, the default actions are going to be used. \
     * The default actions are:
     * - Whoops handler init
     * - DotEnv init 
     * @param array $initActions
     * @return App
     */
    public function setInitActions(array $initActions): self
    {
        $this->initActions = $initActions;
        return $this;
    }

    /**
     * Returns the list of the default init actions.
     * @return class-string<InitActionInterface>[]
     */
    public function getDefaultInitActions(): array
    {
        return $this->defaultInitActions;
    }

    /**
     * Shorthand for `$app->setInitActions($app->getDefaultInitActions());
     * @return App
     */
    public function useDefaultInitActions(): self
    {
        $this->setInitActions($this->getDefaultInitActions());
        return $this;
    }

    // @todo refactor to use some Config object instead of relying on setInitActions
    /**
     * Creates a new application. Sets the init actions passed or the default ones if none. \
     * Default init actions are: 
     * - Whoops handler init 
     * - DotEnv init 
     * @param class-string<InitActionInterface>[] $initActions
     * @return App
     */
    public static function new(...$initActions): self
    {
        return Container::getInstance()->create(App::class)->useDefaultInitActions()->runInitActions();
    }
}