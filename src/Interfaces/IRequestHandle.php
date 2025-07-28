<?php

namespace DumpsterfirePages\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface IRequestHandle
{
    public function handle(Request $request): Request;
}