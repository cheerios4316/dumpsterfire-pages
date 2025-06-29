<?php

namespace DumpsterfirePages\Database;

use DumpsterfirePages\Container\Container;
use DumpsterfirePages\Database\ObjectPropertyHelper\ObjectPropertyHelper;
use DumpsterfirePages\Exceptions\BaseObjectException;
use ReflectionClass;
use ReflectionProperty;

abstract class BaseObject extends DatabaseConnection
{
    protected string $tableName = "";
    protected array $fieldList = [];
    // @todo custom property types
    protected string $primaryName = "";

    protected ObjectPropertyHelper $objectPropertyHelper;

    public function __construct(ObjectPropertyHelper $objectPropertyHelper)
    {
        parent::__construct();

        $this->getFieldList();

        $this->objectPropertyHelper = $objectPropertyHelper;
    }

    public function __get($property)
    {
        if(!in_array($property, $this->fieldList)) {
            throw new BaseObjectException("Cannot access '$property': not a column.");
        }

        return $this->$property;
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

    /**
     * Summary of getAll
     * @param array{column: string, sort: 'ASC'|'DESC'}|[] $sort
     * @return BaseObject[]
     */
    public static function getAll(array $sort = []): array
    {
        $object = static::getNewObject();

        $tableName = $object->tableName;

        $query = "SELECT * FROM " . $tableName;

        if(!empty($sort)) {
            $query .= (' ORDER BY ' . $sort['column'] . ' ' . $sort['sort']);
        }

        $data = self::$connection->query($query);

        return array_map(function($elem){
            return static::create($elem);
        }, $data);
    }

    protected static function getNewObject(): static
    {
        return Container::getInstance()->create(static::class);
    }

    protected function getFieldList(): array
    {
        if(empty($this->fieldList)) {
            $this->fieldList = $this->fetchColumnList();
        }

        return $this->fieldList;
    }

    private function fetchColumnList(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();

        $list = [];

        /** @var \ReflectionProperty $prop */
        foreach($properties as $prop) {
            $propertyData = $this->objectPropertyHelper->getPropertyData($prop);

            if($propertyData->isColumn()) {
                $list[] = $prop->getName();
            }

            if($propertyData->isPrimary()) {
                $this->primaryName = $prop->getName();
            }
        }

        return $list;
    }
}