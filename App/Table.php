<?php

abstract class Table
{
    protected $xml;
    protected $map = array();
    protected $object = array();
    protected $table = null;

    public function __get($table)
    {
        $result = $this->map[$table]->value($this->xml);

        return $result;
    }

    public function getObject()
    {
        $object = null;

        foreach ($this->map as $key => $field) {
            $object[$key] = $field->value($this->xml);
        }
        $this->object = $object;

        return $object;
    }

    public function apply()
    {
        $sqlObject = new MappedSQL($this->map, $this->table);
        return $sqlObject->apply();
    }

    public function applyEntity()
    {
        $sqlObject = new MappedSQL($this->map, $this->table);
        return $sqlObject->applyEntity();
    }

}