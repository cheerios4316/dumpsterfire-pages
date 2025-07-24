<?php

namespace DumpsterfirePages;

use DumpsterfirePages\Container\Container;
use DumpsterfirePages\Exceptions\ContainerException;
use DumpsterfirePages\Interfaces\IRenderable;
use DumpsterfirePages\Interfaces\ISetup;
use DumpsterfirePages\Interfaces\RendererInterface;
use DumpsterfirePages\Renderer\ComponentRenderer;
use ReflectionException;

/**
 * Base Component class.
 * Extend this class to create your own component.
 */
abstract class Component implements IRenderable
{
    protected bool $enabled = true;

    /**
     * Default renderer for the component.
     * @var class-string<RendererInterface>
     */
    protected string $defaultRenderer = ComponentRenderer::class;

    /**
     * Prints the HTML content of a component.
     * @return void
     */
    public function render(): void
    {
        echo $this->content();
    }

    /**
     * Returns the HTML content of a component.
     *
     * @return string
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function content(): string
    {
        if(!$this->isEnabled()) {
            return '';
        }

        $this->preRender();

        return $this->getComponentRenderer()->loadComponent($this)->getContent();
    }

    /**
     * Disables a component: its content will be empty.
     *
     * @return $this
     */
    public function disable(): self
    {
        $this->enabled = false;

        return $this;
    }

    /**
     * Enables a component.
     *
     * @return $this
     */
    public function enable(): self
    {
        $this->enabled = true;

        return $this;
    }

    /**
     * Returns true if the component is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Runs the setup method if the component implements the ISetup interface.
     *
     * @return void
     */
    protected function preRender(): void
    {
        if ($this instanceof ISetup) {
            $this->setup();
        }
    }

    /**
     * Returns the renderer the component needs to use.
     * Defaults to ComponentRenderer.
     *
     * @param class-string<RendererInterface>|null $className
     * @return RendererInterface
     * @throws ContainerException
     * @throws ReflectionException
     */
    protected function getComponentRenderer(?string $className = null): RendererInterface
    {
        if ($className) {
            return Container::getInstance()->create($className);
        }

        return Container::getInstance()->create($this->defaultRenderer);
    }
}