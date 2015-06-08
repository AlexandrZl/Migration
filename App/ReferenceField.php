<?php

class ReferenceField extends Fields
{
    private $class;
    private $name;

    public function __construct ($class, $name)
    {
        $this->class = $class;
        $this->name = $name;
        $sqlObj = new TemplatedSQL();
        $sqlObj->createEntity($name);
    }

    protected function getValuePath()
    {

        $obj = $this->xmlObj->xpath($this->name)[0];

        $itemClass = new $this->class($obj, $this->name);
        $id = $itemClass->apply();

        $this->fieldName = $this->name;
        $this->fieldValue = $id;

        return $id;
    }
}