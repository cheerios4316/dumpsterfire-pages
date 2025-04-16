<?php

namespace DumpsterfirePages\PageTemplate\StaticComponent;

use DumpsterfirePages\Component;

class StaticComponent extends Component
{
    protected string $htmlContent = '';

    public function setHtmlContent(string $content): self
    {
        $this->htmlContent = $content;
        return $this;
    }

    public function content(): string
    {
        return $this->htmlContent;
    }
}