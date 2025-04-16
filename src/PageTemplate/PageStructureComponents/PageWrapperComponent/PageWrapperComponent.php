<?php

namespace DumpsterfirePages\PageTemplate\PageStructureComponents\PageWrapperComponent;

use DumpsterfirePages\Component;

class PageWrapperComponent extends Component
{
    /**
     * @var Component[]
     */
    protected array $items = [];

    /**
     * @return Component[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Component[] $items
     * @return $this
     */
    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }
}