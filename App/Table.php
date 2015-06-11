<?php

abstract class Table
{
    protected $xmlObj;
    protected $map = array();
    protected $entity;
    protected $ref = array();

    public function apply()
    {
        foreach ($this->map as $key => $field) {
            if ($field instanceof ReferenceFieldMultiple || $field instanceof ReferenceField) {
                $this->createEmptyEntity();
                if ($field instanceof ReferenceFieldMultiple) {
                    $field->value($this->xmlObj);
                    $this->ref[] = $field->getName();
                    continue;
                }
            }
            $field->value($this->xmlObj);
        }

        $names = $this->ref ? $this->ref : null;
        $sqlObject = new MappedSQL($this->map, $this->entity);
        return $sqlObject->apply($names);
    }

    protected function createEmptyEntity()
    {
        $sqlObject = new MappedSQL($this->map, $this->entity);
        return $sqlObject->emptyEntity();
    }

}