<?php

namespace DumpsterfirePages\Database;

use ReflectionClass;
use ReflectionProperty;

abstract class BaseObject extends DatabaseConnection
{
    private array $fieldList = [];
    private string $primaryName = "";

    public function __construct()
    {
        parent::__construct();

        $this->getFieldList();
    }

    protected function getFieldList(): array
    {
        if(empty($this->fieldList)) {
            $this->fieldList = $this->fetchFieldList();
        }

        return $this->fieldList;
    }

    private function fetchFieldList(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();

        $list = [];

        /** @var \ReflectionProperty $prop */
        foreach($properties as $prop) {
            if($this->isColumn($prop)) {
                $list[] = $prop->getName();
            }
        }

        return $list;
    }

    private function isColumn(ReflectionProperty $property): bool
    {
        $doc = $property->getDocComment();

        if(!$doc) {
            return false;
        }

        preg_match('/@column(?<extra>.*?)\n/', $doc, $matches);

        if(count($matches) < 1) {
            return false;
        }
            
        if(isset($matches['extra'])) {
            $this->parseExtra($matches['extra'], $property);
        }

        return true;
    }

    private function parseExtra(string $extra, ReflectionProperty $property): void
    {
        $extra = trim($extra);
        $extra = rtrim($extra, ';');

        $values = explode(' ', $extra);

        foreach($values as $val) {
            switch($val) {
                case 'primary':
                    $this->primaryName = $property->getName();
            }
        }
    }
}