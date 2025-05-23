<?php

namespace DumpsterfirePages\AssetsManager\AssetObjects;

use DumpsterfirePages\Interfaces\AssetInterface;

abstract class BaseAsset implements AssetInterface
{
    protected string $path = '';

    protected bool $preload = false;

    public function render(): void
    {
        echo $this->content();
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function setPreload(bool $preload): self
    {
        $this->preload = $preload;
        return $this;
    }
}