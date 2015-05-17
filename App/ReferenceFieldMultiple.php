<?php

class ReferenceFieldMultiple extends Fields
{
    private $reference;
    private $entity;
    private $entities;
    private $created_entity = false;
    private $objects;

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

    public function getObjects()
    {
        return $this->objects;
    }

    protected function getValuePath()
    {
        $xml = $this->xml->xpath($this->entities);
        $objects = array();
        foreach ($xml as $object) {
            foreach ($object as $child) {
                $objects[] = $child;
            }
        }
        $this->objects = $objects;

        return $objects;
    }
}