<?php

namespace DumpsterfireComponents\Renderer\ComponentPath\PathBuilder;

interface PathBuilderInterface
{
    public function build(string $path): string;
}