<?php

namespace DumpsterfirePages\Renderer\ComponentPath\PathBuilder;

interface PathBuilderInterface
{
    public function build(string $path): string;
}