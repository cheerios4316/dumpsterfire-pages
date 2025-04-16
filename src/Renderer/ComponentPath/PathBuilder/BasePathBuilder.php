<?php

namespace DumpsterfireComponents\Renderer\ComponentPath\PathBuilder;

abstract class BasePathBuilder implements PathBuilderInterface
{
    protected string $prefix = "";
    protected string $filetype = "";
    public function build(string $path): string
    {
        $pathData = pathinfo($path);

        $prefix = $this->forceDotRight($this->prefix);
        $filetype = $this->forceDotLeft($this->filetype);

        return $pathData["dirname"] . DIRECTORY_SEPARATOR . $prefix . $pathData["filename"] . $filetype;
    }

    private function forceDotLeft(string $string): string
    {
        return "." . ltrim($string, ".");
    }

    private function forceDotRight(string $string): string
    {
        return rtrim($string, ".") . ".";
    }
}