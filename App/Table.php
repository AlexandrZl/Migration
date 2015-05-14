<?php

abstract class Table
{
    protected $xml;
    protected $map = array();
    protected $object = array();

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

}