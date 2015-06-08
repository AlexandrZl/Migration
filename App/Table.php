<?php

abstract class Table
{
    protected $xmlObj;
    protected $map = array();
    protected $entity;

    public function apply()
    {
        foreach ($this->map as $key => $field) {
            $field->value($this->xmlObj);
        }

        $sqlObject = new MappedSQL($this->map, $this->entity);
        return $sqlObject->apply();
    }

}