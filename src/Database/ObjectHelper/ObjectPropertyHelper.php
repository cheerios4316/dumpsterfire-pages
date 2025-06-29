<?php

namespace DumpsterfirePages\Database\ObjectPropertyHelper;

use DumpsterfirePages\Container\Container;
use ObjectPropertyData;
use ReflectionProperty;

class ObjectPropertyHelper
{
    /** @var array<string, ObjectPropertyData> $cache */
    protected static array $cache = [];

    /** @var array<string, string> $extra */
    protected static array $extra = [];

    public function __construct(protected Container $container) {}

    public function getPropertyData(ReflectionProperty $property): ObjectPropertyData
    {
        $name = $property->getName();
        if(isset(self::$cache[$name])) {
            return self::$cache[$name];
        }

        $object = $this->container->create(ObjectPropertyData::class);

        if($this->isColumn($property)) {
            $object->setIsColumn(true);
        }

        if(isset(self::$extra[$name])) {
            $object = $this->parseExtra(self::$extra[$name], $property, $object);
        }

        return $object;
    }

    public function isPropertyType(ReflectionProperty $property, string $type): bool
    {
        $doc = $property->getDocComment();

        if(!$doc) {
            return false;
        }

        preg_match('/@' . $type . ' (?<extra>.*?)(?:\*\/)?\n?$/', $doc, $matches);

        if(count($matches) < 1) {
            return false;
        }
            
        if(isset($matches['extra'])) {
            self::$extra[$property->getName()] = $matches['extra'];
        } else {
            self::$extra[$property->getName()] = "";
        }

        return true;
    }

    public function isColumn(ReflectionProperty $property): bool
    {
        return $this->isPropertyType($property, "column");
    }

    public function isProperty(ReflectionProperty $property): bool
    {
        return $this->isPropertyType($property, "property");
    }

    private function parseExtra(string $extra, ReflectionProperty $property, ObjectPropertyData $object): ObjectPropertyData
    {
        $extra = trim($extra);
        $extra = rtrim($extra, ';');
        $extra = rtrim($extra, '*/');

        $values = explode(' ', $extra);

        foreach($values as $val) {
            switch($val) {
                case 'primary':
                    $object->setIsPrimary(true);
            }
        }

        return $object;
    }
}