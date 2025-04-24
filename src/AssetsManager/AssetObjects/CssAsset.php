<?php

namespace DumpsterfirePages\AssetsManager\AssetObjects;

class CssAsset extends BaseAsset
{
    public function content(): string
    {
        return $this->preload ?
            "<link rel='preload' href='{$this->path}' as='style'> onload=\"this.onload=null;this.rel='stylesheet'\"" :
            "<link rel='stylesheet' href='{$this->path}'>";
    }
}