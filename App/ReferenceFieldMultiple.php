<?php

class ReferenceFieldMultiple extends Fields
{
    private $reference;
    private $entity;
    private $entities;
    private $map = array();
    private $ids;
    private $created_entity = false;

    public function __construct ($entity, $entities, $reference)
    {
        $this->reference = $reference;
        $this->entities = $entities;
        $this->entity = $entity;
        $this->created_entity = MappedSQL::createMap($entity, $reference);
        if (!$this->created_entity) {
            $this->created_entity = $reference."_".$entity;
        }
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

    public function getCreatedEntity()
    {
        return $this->created_entity;
    }

    public function getEntities()
    {
        return $this->entities;
    }

    protected function getValuePath()
    {
        $xml = $this->xml->xpath($this->entities);
        $objects = array();
        $i = 0;
        $id = array();

        foreach ($xml as $object) {
            foreach ($object as $child) {
                foreach ($this->map as $key => $field) {
                    $objects[$i][$key] = $field->value($child);
                    if ($field instanceof PrimaryField) {
                        $id[] = $field->fieldValue;
                    }
                }
                $this->mappedDb($this->map, $this->entity);
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