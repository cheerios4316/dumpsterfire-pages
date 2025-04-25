<?php

namespace DumpsterfirePages\Interfaces;

use DumpsterfirePages\Component;

interface RendererInterface
{
    /**
     * @param Component $component
     * @return self
     */
    public function loadComponent(Component $component): self;
    public function getContent(): string;
}