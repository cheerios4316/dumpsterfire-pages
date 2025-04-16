<?php

namespace DumpsterfireComponents\PageTemplate\PageStructureComponents\HtmlPageSkeletonComponent;

use DumpsterfireComponents\Component;

class HtmlPageSkeletonComponent extends Component
{
    protected string $htmlContent;
    protected string $lang = 'en';
    protected string $headContent = '';

    protected string $bodyContent = '';

    public function setHtmlContent(string $content): self
    {
        $this->htmlContent = $content;
        return $this;
    }

    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function setLang(): string
    {
        return $this->lang;
    }

    public function getHeadContent(): string
    {
        return $this->headContent;
    }

    public function setHeadContent(string $headContent): static
    {
        $this->headContent = $headContent;
        return $this;
    }

    public function getBodyContent(): string
    {
        return $this->bodyContent;
    }

    public function setBodyContent(string $bodyContent): static
    {
        $this->bodyContent = $bodyContent;
        return $this;
    }
}