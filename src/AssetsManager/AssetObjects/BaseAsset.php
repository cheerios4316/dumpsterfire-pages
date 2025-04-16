<?php

namespace DumpsterfireComponents\AssetsManager\AssetObjects;

use DumpsterfireComponents\Interfaces\AssetInterface;

abstract class BaseAsset implements AssetInterface
{
    protected string $path = '';
    public function render(): void
    {
        echo $this->content();
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }
}