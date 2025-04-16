<?php

namespace DumpsterfireComponents;

use DumpsterfireBase\Container\Container;
use DumpsterfireComponents\Interfaces\IRenderable;
use DumpsterfireComponents\Interfaces\ISetup;
use DumpsterfireComponents\Interfaces\RendererInterface;
use DumpsterfireComponents\Renderer\ComponentRenderer;

/**
 * Base Component class.
 * Extend this class to create your own component.
 */
abstract class Component implements IRenderable
{
    protected bool $enabled = true;

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
     */
    public function content(): string
    {
        if(!$this->isEnabled()) {
            return '';
        }

        $this->preRender();

        return $this->getComponentRenderer()->loadComponent($this)->getHtmlContent();
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
     * May be subject to modifications.
     *
     * @return void
     */
    private function preRender(): void
    {
        if ($this instanceof ISetup) {
            $this->setup();
        }
    }

    protected function getComponentRenderer(): RendererInterface
    {
        return Container::getInstance()->create(ComponentRenderer::class);
    }
}