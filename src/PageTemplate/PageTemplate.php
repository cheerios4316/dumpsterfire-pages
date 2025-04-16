<?php

namespace DumpsterfireComponents\PageTemplate;

use DumpsterfireBase\Container\Container;
use DumpsterfireComponents\Component;
use DumpsterfireComponents\Exceptions\ComponentRendererException;
use DumpsterfireComponents\PageComponent;
use DumpsterfireComponents\PageTemplate\StaticComponent\StaticComponent;
use DumpsterfireComponents\Renderer\ComponentRenderer;

class PageTemplate
{
    /** @var ?class-string<Component> $header */
    protected static ?string $header = null;

    /** @var ?class-string<Component> $footer */
    protected static ?string $footer = null;

    /**
     * @param class-string<Component> $header
     * @return void
     */
    public static function setHeader(string $header): void
    {
        self::$header = $header;
    }

    /**
     * @param class-string<Component> $footer
     * @return void
     */
    public static function setFooter(string $footer): void
    {
        self::$footer = $footer;
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     */
    protected static function containerGet(string $class)
    {
        return Container::getInstance()->create($class);
    }

    public static function getHeaderComponent(): ?Component
    {
        if(self::$header) {
            return self::containerGet(self::$header);
        }

        return null;
    }

    public static function getFooterComponent(): ?Component
    {
        if(self::$footer) {
            return self::containerGet(self::$footer);
        }

        return null;
    }

    /**
     * @param PageComponent|null $component
     * @return Component[]
     * @throws ComponentRendererException
     */
    public static function getDefaultTemplate(?PageComponent $component): array
    {
        $templateHeader = PageTemplate::getHeaderComponent();
        $templateFooter = PageTemplate::getFooterComponent();

        $header = $component->getHeaderComponent() ?? $templateHeader;
        $footer = $component->getFooterComponent() ?? $templateFooter;

        $componentRenderer = self::getContainer()->create(ComponentRenderer::class)->loadComponent($component);

        $staticPageComponent = self::getContainer()->create(StaticComponent::class)
            ->setHtmlContent($componentRenderer->getHtmlContent());

        $components = [$header, $staticPageComponent, $footer];
        return array_filter($components, fn($elem) => $elem !== null);
    }

    protected static function getContainer(): Container
    {
        return Container::getInstance();
    }
}