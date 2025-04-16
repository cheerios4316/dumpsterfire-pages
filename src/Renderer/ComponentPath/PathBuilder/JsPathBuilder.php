<?php

namespace DumpsterfireComponents\Renderer\ComponentPath\PathBuilder;

class JsPathBuilder extends BasePathBuilder
{
    protected string $prefix = "script";
    protected string $filetype = "js";

    public function build(string $path): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . preg_replace('/^\/var\/www\/html\//', '/public/js/', parent::build($path));
    }
}