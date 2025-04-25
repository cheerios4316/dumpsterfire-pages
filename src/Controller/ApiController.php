<?php

namespace DumpsterfirePages\Controllers;

use DumpsterfirePages\Controller\BaseController;
use DumpsterfirePages\JsonPageComponent;

abstract class ApiController extends BaseController
{
    protected JsonPageComponent $page;

    public function __construct(JsonPageComponent $jsonPageComponent)
    {
        $this->page = $jsonPageComponent;
    }

    public function getResult(): JsonPageComponent
    {
        return $this->page->setData($this->getData());
    }

    abstract function getData(): array;
}