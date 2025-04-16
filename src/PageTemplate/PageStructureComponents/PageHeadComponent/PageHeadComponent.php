<?php

namespace DumpsterfireComponents\PageTemplate\PageStructureComponents\PageHeadComponent;

use DumpsterfireComponents\Component;
use DumpsterfireComponents\PageTemplate\PageStructureComponents\DependenciesComponent\DependenciesComponent;

class PageHeadComponent extends Component
{
    protected string $title = '';
    protected array $meta = ['charset' => 'utf-8'];
    protected DependenciesComponent $dependenciesComponent;

    public function setDependenciesComponent(DependenciesComponent $dependenciesComponent): self
    {
        $this->dependenciesComponent = $dependenciesComponent;
        return $this;
    }

    public function getDependenciesComponent(): DependenciesComponent
    {
        return $this->dependenciesComponent;
    }

    public function setMeta(array $meta): self
    {
        $this->meta = $meta;
        return $this;
    }

    public function getMetaHtml(): string
    {
        $html = '';

        foreach($this->meta as $name => $content) {
            $html .= "<meta name=\"$name\" content=\"$content\">\n\t";
        }
        return $html;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

}