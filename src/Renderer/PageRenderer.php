<?php

namespace DumpsterfireComponents\Renderer;

use DumpsterfireBase\Container\Container;
use DumpsterfireComponents\AssetsManager\AssetsManager;
use DumpsterfireComponents\Component;
use DumpsterfireComponents\Exceptions\ComponentRendererException;
use DumpsterfireComponents\Exceptions\PageRendererException;
use DumpsterfireComponents\Interfaces\RendererInterface;
use DumpsterfireComponents\PageComponent;
use DumpsterfireComponents\PageTemplate\PageStructureComponents\DependenciesComponent\DependenciesComponent;
use DumpsterfireComponents\PageTemplate\PageStructureComponents\HtmlPageSkeletonComponent\HtmlPageSkeletonComponent;
use DumpsterfireComponents\PageTemplate\PageStructureComponents\PageHeadComponent\PageHeadComponent;
use DumpsterfireComponents\PageTemplate\PageStructureComponents\PageWrapperComponent\PageWrapperComponent;
use DumpsterfireComponents\PageTemplate\PageTemplate;
use DumpsterfireComponents\PageTemplate\StaticComponent\StaticComponent;

class PageRenderer implements RendererInterface
{
    protected ?PageComponent $component = null;
    protected ComponentRenderer $componentRenderer;
    protected Container $container;
    protected AssetsManager $assetsManager;

    public function __construct(ComponentRenderer $componentRenderer, Container $container, AssetsManager $assetsManager)
    {
        $this->componentRenderer = $componentRenderer;
        $this->container = $container;
        $this->assetsManager = $assetsManager;
    }

    /**
     * @param PageComponent $component
     * @return RendererInterface
     * @throws PageRendererException
     */
    public function loadComponent(Component $component): RendererInterface
    {
        if(!$component instanceof PageComponent) {
            throw new PageRendererException('PageRenderer expects a PageComponent.');
        }

        $this->component = $component;

        return $this;
    }

    /**
     * @return string
     * @throws PageRendererException|ComponentRendererException
     */
    public function getHtmlContent(): string
    {
        $this->assetsManager->loadDefaults();
        if(!$this->component) {
            throw new PageRendererException('No PageComponent loaded.');
        }

        $components = $this->getArrayComponents();

        $pageWrapper = $this->container->create(PageWrapperComponent::class)->setItems($components);

        // Rendering the page early to fully populate the AssetsManager dependency list.
        // (this includes both header and footer component)
        $pageRender = $pageWrapper->content();

        return $this->buildPage($pageRender);
    }

    protected function buildPage(string $content): string
    {
        $staticComponent = $this->container->create(StaticComponent::class);

        $head = $this->container->create(PageHeadComponent::class);

        $head
            ->setMeta($this->getMetaWithDefault())
            ->setDependenciesComponent($this->generateDependenciesComponent())
            ->setTitle($this->component->getTitle())
        ;

        $htmlPage = $this->container->create(HtmlPageSkeletonComponent::class);
        $htmlPage
            ->setHeadContent($head->content())
            ->setBodyContent($content)
        ;

        $staticComponent->setHtmlContent($htmlPage->content());

        return $staticComponent->content();
    }

    protected function getMetaWithDefault(): array
    {
        return array_merge([
            'charset' => 'UTF-8',
            'http-equiv' => 'X-UA-Compatible',
            'content' => 'IE=edge',
            'viewport' => 'width=device-width, initial-scale=1',
        ], $this->component->getMeta());
    }

    /**
     * @return Component[]
     * @throws ComponentRendererException
     */
    protected function getArrayComponents(): array
    {
        return PageTemplate::getDefaultTemplate($this->component);
    }

    protected function generateDependenciesComponent()
    {
        $dependencies = $this->container->create(DependenciesComponent::class);

        $dependencies->setAssets(AssetsManager::getDependencies());

        return $dependencies;
    }
}