<?php

namespace DumpsterfirePages\Database;

use DumpsterfirePages\Container\Container;
use ReflectionClass;
use ReflectionProperty;

abstract class BaseObject extends DatabaseConnection
{
    protected string $tableName = "";
    protected array $fieldList = [];
    protected string $primaryName = "";

    public function __construct()
    {
        parent::__construct();

        $this->getFieldList();
    }

    public function getArray(): array
    {
        $arr = [];
        foreach($this->fieldList as $field) {
            $arr[$field] = $this->$field;
        }

        return $arr;
    }

    public static function create(array $data): static
    {
        $object = static::getNewObject();

        $fieldList = $object->fieldList;

        foreach($data as $key => $val) {
            if(!property_exists($object, $key)) {
                continue;
            }

            if(!in_array($key, $fieldList)) {
                continue;
            }

            $object->$key = $val;
        }

        return $object;
    }

    public static function searchByPrimary($value): ?static
    {
        $object = static::getNewObject();

        $fieldList = $object->fieldList;
        $primary = $object->primaryName;
        $tableName = $object->tableName;

        $query = "SELECT * FROM " . $tableName . " WHERE " . $primary . " = :value";
        
        $params = [
            "value" => $value
        ];

        $data = self::$connection->query($query, $params);

        if(empty($data)) {
            return null;
        }

        return static::create($data[0]);
    }

    protected static function getNewObject(): static
    {
        return Container::getInstance()->create(static::class);
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

        preg_match('/@column (?<extra>.*?)(?:\*\/)?\n?$/', $doc, $matches);

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
        $extra = rtrim($extra, '*/');

        $values = explode(' ', $extra);

        foreach($values as $val) {
            switch($val) {
                case 'primary':
                    $this->primaryName = $property->getName();
            }
        }
    }
}