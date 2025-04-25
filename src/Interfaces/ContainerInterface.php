<?php

namespace DumpsterfirePages\Interfaces;

interface ContainerInterface
{
    /**
     * @template T
     * @param class-string<T> $class
     * @return T|null
     */
    public function create(string $class);
}