<?php

namespace DumpsterfireComponents\ComponentData;

use DumpsterfireBase\Hydrator\BaseHydrator;

class ComponentDataObject extends BaseHydrator
{
    protected ?string $classPath = null;
    protected ?string $view = null;
    protected ?string $js = null;
    protected ?string $css = null;

    /**
     * @param array{classPath: string, view: string, js: string, css: string} $data
     * @return self
     */
    public function hydrate(array $data): self
    {
        return parent::hydrate($data);
    }

    public function hasFile(string $path): bool
    {
        return file_exists($path);
    }

    public function getClassPath(): ?string
    {
        return $this->classPath;
    }

    public function getViewPath(): ?string
    {
        return $this->view;
    }

    public function getJsPath(): ?string
    {
        return $this->js;
    }

    public function getCssPath(): ?string
    {
        return $this->css;
    }
}