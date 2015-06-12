<?php

abstract class Table
{
    protected $xmlObj;
    protected $map = array();
    public $primaryField;
    public $entity;
    protected $ref = array();

    public function apply()
    {
        $this->primaryField->value($this->xmlObj);
        $id = $this->primaryField->getObj($this->entity);
        foreach ($this->map as $key => $field) {
            $field->value($this->xmlObj);
        }

        $sqlObject = new MappedSQL($this->map, $this->entity);
        return $sqlObject->apply($id);
    }

    public function getObj()
    {
        return $this->map;
    }

}