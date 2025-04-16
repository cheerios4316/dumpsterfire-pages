<?php

namespace DumpsterfirePages\Interfaces;

use DumpsterfirePages\PageComponent;

interface ControllerInterface
{
    public function getResult(): PageComponent;
}