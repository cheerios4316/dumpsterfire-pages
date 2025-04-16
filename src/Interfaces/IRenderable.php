<?php

namespace DumpsterfireComponents\Interfaces;

interface IRenderable
{
    public function content(): string;
    public function render(): void;
}