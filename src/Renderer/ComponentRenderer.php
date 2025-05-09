<?php

namespace DumpsterfirePages\Renderer;

use DumpsterfirePages\AssetsManager\AssetsManager;
use DumpsterfirePages\Component;
use DumpsterfirePages\ComponentData\ComponentDataManager;
use DumpsterfirePages\ComponentData\ComponentDataObject;
use DumpsterfirePages\Exceptions\ComponentRendererException;
use DumpsterfirePages\Interfaces\RendererInterface;

class ComponentRenderer implements RendererInterface
{
    protected ?Component $component = null;

    protected AssetsManager $assetsManager;

    public function __construct(AssetsManager $assetsManager)
    {
        $this->assetsManager = $assetsManager;
    }

    public function loadComponent(Component $component): self
    {
        $this->component = $component;

        return $this;
    }

    /**
     * @return string
     * @throws ComponentRendererException
     */
    public function getContent(): string
    {
        $componentData = $this->getComponentData();

        return $this->getViewContent($componentData->getViewPath());
    }


    /**
     * @return ComponentDataObject
     * @throws ComponentRendererException
     */
    protected function getComponentData(): ComponentDataObject
    {
        $component = $this->getComponent();

        $componentDataManager = ComponentDataManager::getInstance();

        return $componentDataManager->getComponentData($component);
    }

    /**
     * @param string $view
     * @return string
     * @throws ComponentRendererException
     */
    protected function getViewContent(string $view): string
    {
        if(!file_exists($view)) {
            return '';
        }

        $component = $this->getComponent();

        ob_start();

        $includeClosure = function () use ($view) {
            require $view;
        };

        $boundClosure = $includeClosure->bindTo($component, get_class($component));

        $boundClosure();

        return ob_get_clean();
    }

    /**
     * @throws ComponentRendererException
     */
    protected function getComponent(): Component
    {
        if(!$this->component) {
            throw new ComponentRendererException("Cannot use ComponentRenderer without loading a component first!");
        }

        return $this->component;
    }
}