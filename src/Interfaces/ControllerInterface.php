<?php

namespace DumpsterfirePages\Interfaces;

interface ControllerInterface
{
    /**
     * @return PageInterface
     */
    public function getResult();
}