<?php

namespace DumpsterfirePages\RequestManager;

use DumpsterfirePages\Container\Container;
use Symfony\Component\HttpFoundation\Request;
use DumpsterfirePages\Interfaces\IRequestHandle;

class RequestManager
{
    protected ?Request $request = null;

    /** @var class-string<IRequestHandle>[]  */
    protected array $handlers = [];

    protected function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handleRequest(): self
    {
        $request = $this->request;
        $container = Container::getInstance();

        foreach($this->handlers as $handler) {
            $instance = $container->get($handler);
            $request = $instance->handle($request);
        }

        $this->request = $request;

        return $this;
    }

    /**
     * @param class-string<IRequestHandle>[] $handlers
     * @return RequestManager
     */
    protected function setHandlers(array $handlers): self
    {
        $this->handlers = $handlers;
        return $this;
    }

    /**
     * @param class-string<IRequestHandle>[] $handlers
     * @return RequestManager
     */
    public static function Obtain(...$handlers): self
    {
        $request = Request::createFromGlobals();

        $instance = new self($request);
        $instance->setHandlers($handlers);

        return $instance;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}