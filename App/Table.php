<?php

abstract class Table
{
    protected $xmlObj;
    protected $map = array();
    protected $entity;
    protected static $ref = array();

    public function apply()
    {
        foreach ($this->map as $key => $field) {
            if ($field instanceof ReferenceFieldMultiple || $field instanceof ReferenceField) {
                $this->createEmptyEntity();
            }
            $field->value($this->xmlObj);
        }

        $sqlObject = new MappedSQL($this->map, $this->entity);
        return $sqlObject->apply();
    }

    protected function createEmptyEntity()
    {
        $sqlObject = new MappedSQL($this->map, $this->entity);
        return $sqlObject->emptyEntity();
    }

    public static function getRef($key)
    {
        $empty = [];
        if (isset(self::$ref[$key])) {
            return self::$ref[$key];
        }
        return $empty;
    }

    public static function setRef($key, $value)
    {
        self::$ref[$key] = $value;
        return self::$ref;
    }


}