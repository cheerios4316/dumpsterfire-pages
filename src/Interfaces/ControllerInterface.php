<?php

namespace DumpsterfireRouter\Interfaces;

interface ControllerInterface
{
    public function getResult(): PageResponse;
}