<?php

namespace DumpsterfirePages\App;

use DumpsterfirePages\Container\Container;
use DumpsterfirePages\InitActions\DotEnvInit;
use DumpsterfirePages\InitActions\WhoopsInit;
use DumpsterfirePages\Interfaces\LoggerInterface;
use DumpsterfirePages\Component;
use DumpsterfirePages\PageComponent;
use DumpsterfirePages\PageTemplate\PageTemplate;
use DumpsterfirePages\Interfaces\RouterInterface;
use DumpsterfirePages\Interfaces\InitActionInterface;
use DumpsterfirePages\Interfaces\ILoggable;
use DumpsterfirePages\Router\DumpsterfireRouter;

class App implements ILoggable
{
    /** @var class-string<InitActionInterface>[] $initActions */
    protected array $initActions = [];

    /** @var class-string<InitActionInterface>[] $defaultInitActions */
    protected array $defaultInitActions = [
        DotEnvInit::class,
        WhoopsInit::class,
    ];

    /**
     * @var PageComponent|null
     */
    protected ?PageComponent $page404Component = null;

    /** @var RouterInterface|null  */
    protected ?RouterInterface $router = null;

    /**
     * @param RouterInterface $routerInterface
     * @return $this
     */
    public function setRouter(RouterInterface $routerInterface): self
    {
        $this->router = $routerInterface;

        if($this->page404Component && $this->router instanceof DumpsterfireRouter) {
            $this->router->set404PageComponent($this->page404Component);
        }

        return $this;
    }

    public function run(): self
    {
        $request = $_SERVER;

        $requestUri = $request['REDIRECT_URL'];

        if($this->router) {
            $controller = $this->router->getControllerFromRoute($requestUri);
            $controller->getResult()->render();
        }

        return $this;
    }

    public function runInitActions(): self
    {
        foreach($this->initActions as $action) {

            $action = Container::getInstance()->create($action);
            $action->run();
        }

        return $this;
    }

    /**
     * @param LoggerInterface $loggerInterface
     * @return $this
     */
    public function setLogger(LoggerInterface $loggerInterface): self
    {
        Container::setLogger($loggerInterface);
        return $this;
    }

    public function set404PageComponent(PageComponent $pageComponent): self
    {
        $this->page404Component = $pageComponent;

        return $this;
    }

    /**
     * @param class-string<Component> $component
     * @return App
     */
    public function setPageTemplateHeader(string $component): self
    {
        PageTemplate::setHeader($component);
        return $this;
    }

    /**
     * @param class-string<Component> $component
     * @return App
     */
    public function setPageTemplateFooter(string $component): self
    {
        PageTemplate::setFooter($component);
        return $this;
    }

    public function getInitActions(): array
    {
        return $this->initActions;
    }

    public function setInitActions(array $initActions): self
    {
        $this->initActions = $initActions;
        return $this;
    }

    public function getDefaultInitActions(): array
    {
        return $this->defaultInitActions;
    }

    public function useDefaultInitActions(): self
    {
        $this->setInitActions($this->getDefaultInitActions());
        return $this;
    }

    public static function new(): self
    {
        return Container::getInstance()->create(self::class)->useDefaultInitActions();
    }
}