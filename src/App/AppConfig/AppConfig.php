<?php

namespace DumpsterfirePages\App\AppConfig;

use DumpsterfirePages\App\App;
use DumpsterfirePages\Component;
use DumpsterfirePages\Container\Container;
use DumpsterfirePages\Exceptions\DumpsterfirePagesException;
use DumpsterfirePages\Interfaces\InitActionInterface;
use DumpsterfirePages\PageTemplate\PageTemplate;

class AppConfig
{
    protected ?Component $header = null;
    protected ?Component $footer = null;

    /** @var class-string<InitActionInterface>[] */
    protected array $initActions = [];

    public function __construct(protected Container $container) {}

    /**
     * @param object $caller
     * @return void
     * @throws DumpsterfirePagesException
     */
    public function run(object $caller): void {
        if (!$caller instanceof App) {
            throw new DumpsterfirePagesException("Unauthorized: cannot call AppConfig::run() outside of App internal context.");
        }

        if($this->header) {
            PageTemplate::setHeader($this->header);
        }

        if($this->footer) {
            PageTemplate::setFooter($this->footer);
        }

        $this->runInitActions();
    }

    protected function runInitActions(): void {
        foreach($this->initActions as $action) {
            $instance = $this->container->get($action);

            $instance->run();
        }
    }

    public function getHeader(): ?Component
    {
        return $this->header;
    }

    public function setHeader(?Component $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function getFooter(): ?Component
    {
        return $this->footer;
    }

    public function setFooter(?Component $footer): self
    {
        $this->footer = $footer;
        return $this;
    }

    public function getInitActions(): array
    {
        return $this->initActions;
    }

    /**
     * @param class-string<InitActionInterface>[] $initActions
     * @return $this
     */
    public function setInitActions(array $initActions): self
    {
        $this->initActions = $initActions;
        return $this;
    }
}