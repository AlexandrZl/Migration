<?php

abstract class Table
{
    protected $xmlObj;
    protected $map = array();
    protected $entity;

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

}