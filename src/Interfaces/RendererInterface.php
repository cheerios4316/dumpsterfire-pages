<?php

namespace DumpsterfireComponents\Interfaces;

use DumpsterfireComponents\Component;

interface RendererInterface
{
    /**
     * @param Component $component
     * @return self
     */
    public function loadComponent(Component $component): self;
    public function getHtmlContent(): string;
}