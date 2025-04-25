<?php

namespace DumpsterfirePages\AssetsManager;

use DumpsterfirePages\Container\Container;
use DumpsterfirePages\AssetsManager\AssetObjects\CssAsset;
use DumpsterfirePages\AssetsManager\AssetObjects\JsAsset;
use DumpsterfirePages\Interfaces\AssetInterface;

class AssetsManager
{
    protected static array $jsAssets = [];
    protected static array $cssAssets = [];

    /**
     * @return AssetInterface[]
     */
    public static function getDependencies(): array
    {
        $container = Container::getInstance();

        $js = [];
        $css = [];

        foreach (self::$jsAssets as $path) {
            $js[] = $container->create(JsAsset::class)->setPath($path);
        }

        foreach (self::$cssAssets as $path) {
            $css[] = $container->create(CssAsset::class)->setPath($path);
        }

        return array_merge($css, $js);
    }

    public function loadDefaults(): void
    {
        $list = DefaultDependencies::get();

        foreach ($list['js'] ?? [] as $script) {
            self::loadJs($script);
        }
        foreach ($list['css'] ?? [] as $style) {
            self::loadCss($style);
        }
    }

    /**
     * @param string $path
     * @return $this
     */
    public function loadJs(string $path): self
    {
        if (!in_array($path, self::$jsAssets)) {
            self::$jsAssets[] = $path;
        }

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function loadCss(string $path): self
    {
        if (!in_array($path, self::$cssAssets)) {
            self::$cssAssets[] = $path;
        }

        return $this;
    }
}