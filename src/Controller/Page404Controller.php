<?php

namespace DumpsterfirePages\Controller;

use DumpsterfirePages\PageComponent;

class Page404Controller extends BaseController
{
    protected ?PageComponent $pageComponent = null;
    public function set404Page(PageComponent $component): self
    {
        $this->pageComponent = $component;

        return $this;
    }

    public function getResult(): PageComponent
    {
        return $this->pageComponent;
    }
}