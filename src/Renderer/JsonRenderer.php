<?php

namespace DumpsterfirePages\Renderer;

use DumpsterfirePages\Component;
use DumpsterfirePages\Exceptions\PageRendererException;
use DumpsterfirePages\Interfaces\ILoggable;
use DumpsterfirePages\Interfaces\LoggerInterface;
use DumpsterfirePages\Interfaces\RendererInterface;
use DumpsterfirePages\JsonPageComponent;
use Throwable;

class JsonRenderer implements RendererInterface, ILoggable
{
    protected ?JsonPageComponent $component = null;

    protected ?LoggerInterface $logger = null;

    public function getContent(): string
    {
        try {
            header('Content-Type: application/json');
            return json_encode($this->component->getData());
        } catch (Throwable $e) {
            $this->logger?->log($e);
            throw new PageRendererException($e);
        }
    }

    /**
     * Summary of loadComponent
     * @param JsonPageComponent $component
     * @return JsonRenderer
     */
    public function loadComponent(Component $component): RendererInterface
    {
        $this->component = $component;

        return $this;
    }

    public function setLogger(LoggerInterface $loggerInterface): self
    {
        $this->logger = $loggerInterface;

        return $this;
    }
}