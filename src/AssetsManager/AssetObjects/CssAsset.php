<?php

namespace DumpsterfirePages\AssetsManager\AssetObjects;

class CssAsset extends BaseAsset
{
    public function content(): string
    {
        return "<link rel='stylesheet' href='{$this->path}'>";
    }
}