<?php

namespace DumpsterfirePages;

use DumpsterfirePages\Interfaces\PageInterface;
use DumpsterfirePages\Renderer\JsonRenderer;

class JsonPageComponent extends Component implements PageInterface
{
    protected string $defaultRenderer = JsonRenderer::class;

    protected array $data = [];

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }
}