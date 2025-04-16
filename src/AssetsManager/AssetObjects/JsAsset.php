<?php

namespace DumpsterfirePages\AssetsManager\AssetObjects;

class JsAsset extends BaseAsset
{
    public function content(): string
    {
        return "<script type=\"module\" src=\"" . $this->path . "\"></script>";
    }
}