<?php

namespace DumpsterfirePages\PageTemplate\PageStructureComponents\DependenciesComponent;

use DumpsterfirePages\Component;
use DumpsterfirePages\Interfaces\AssetInterface;

class DependenciesComponent extends Component
{
    /** @var AssetInterface[] */
    protected array $assets = [];

    /**
     * @param AssetInterface[] $assets
     * @return $this
     */
    public function setAssets(array $assets): self
    {
        $this->assets = $assets;
        return $this;
    }

    /**
     * @return AssetInterface[]
     */
    public function getAssets(): array
    {
        return $this->assets;
    }
}