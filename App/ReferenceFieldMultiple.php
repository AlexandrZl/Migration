<?php

class ReferenceFieldMultiple extends Fields
{
    private $reference;
    private $entity;
    private $map = array();
    private $ids;

    public function __construct ($entity, $reference)
    {
        $this->reference = $reference;
        $this->entity = $entity;
    }

    public function setFields($fields)
    {
        foreach ($fields as $key => $field) {
            $this->map[$key] = $field;
        }
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getReference()
    {
        return $this->reference;
    }

    protected function getValuePath()
    {
        $xml = $this->xml->xpath($this->reference);
        $objects = array();
        $i = 0;
        $id = array();

        foreach ($xml as $object) {
            foreach ($object as $child) {
                foreach ($this->map as $key => $field) {
                    $objects[$i][$key] = $field->value($child);
                }
                $id[] = $this->mappedDb($this->map, $this->entity);
                $i++;
            }
        }
        $this->ids = array_unique($id);
        return $this->ids;
    }

    public function getIds()
    {
        return $this->ids;
    }

    protected function mappedDb($object, $entity)
    {
        $sqlObject = new MappedSQL($object, $entity);
        return $sqlObject->apply();
    }
}