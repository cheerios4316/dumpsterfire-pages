<?php

namespace DumpsterfireComponents\Renderer;

use DumpsterfireComponents\AssetsManager\AssetsManager;
use DumpsterfireComponents\Component;
use DumpsterfireComponents\ComponentData\ComponentDataManager;
use DumpsterfireComponents\ComponentData\ComponentDataObject;
use DumpsterfireComponents\Exceptions\ComponentRendererException;
use DumpsterfireComponents\Interfaces\RendererInterface;

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
    public function getHtmlContent(): string
    {
        $componentData = $this->getComponentData();

        $js = $componentData->getJsPath();
        $css = $componentData->getCssPath();

        if($componentData->hasFile($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $js)) {
            //$this->assetsManager->loadJs($js);
        }

        if($componentData->hasFile($css)) {
            $this->assetsManager->loadCss($css);
        }

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