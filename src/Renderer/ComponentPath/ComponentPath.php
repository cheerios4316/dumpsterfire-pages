<?php

namespace DumpsterfireComponents\Renderer\ComponentPath;

use DumpsterfireComponents\Component;
use DumpsterfireComponents\Renderer\ComponentPath\PathBuilder\CssPathBuilder;
use DumpsterfireComponents\Renderer\ComponentPath\PathBuilder\JsPathBuilder;
use DumpsterfireComponents\Renderer\ComponentPath\PathBuilder\PathBuilderInterface;
use DumpsterfireComponents\Renderer\ComponentPath\PathBuilder\ViewPathBuilder;

class ComponentPath
{
    protected CssPathBuilder $cssPathBuilder;
    protected JsPathBuilder $jsPathBuilder;
    protected ViewPathBuilder $viewPathBuilder;

    /** @var array<string, string> */
    protected static array $cached = [];

    public function __construct(
        CssPathBuilder  $cssPathBuilder,
        JsPathBuilder   $jsPathBuilder,
        ViewPathBuilder $viewPathBuilder
    )
    {
        $this->cssPathBuilder = $cssPathBuilder;
        $this->jsPathBuilder = $jsPathBuilder;
        $this->viewPathBuilder = $viewPathBuilder;
    }

    public function getDefinitionPath(Component $component): string
    {
        if(isset(self::$cached[$component::class])) {
            return self::$cached[$component::class];
        }

        $reflector = new \ReflectionClass($component);
        $class = $reflector->getFileName();

        self::$cached[$component::class] = $class;
        return $class;
    }

    protected function getPath(PathBuilderInterface $builder, Component $component): string
    {
        $path = $this->getDefinitionPath($component);

        return preg_replace('/^\/var\/www\/html\//', '', $builder->build($path));
    }

    public function getViewPath(Component $component): string
    {
        return $this->getPath($this->viewPathBuilder, $component);
    }

    public function getJsPath(Component $component): string
    {
        return $this->getPath($this->jsPathBuilder, $component);

    }

    public function getCssPath(Component $component): string
    {
        return $this->getPath($this->cssPathBuilder, $component);

    }
}