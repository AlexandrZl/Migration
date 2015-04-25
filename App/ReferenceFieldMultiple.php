<?php

class ReferenceFieldMultiple extends Fields
{
    private $reference;
    private $entity;
    private $map = array();

    public function __construct ($entity, $reference)
    {
        $this->reference = $reference;
        $this->entity = $entity;

        $this->map = array(
            'id' => new PrimaryField('id'),
            'firstName' => new StringField('firstName'),
            'lastName' => new StringField('lastName'),
        );
    }

    public function getEntity()
    {
        return $this->entity;
    }

    protected function getValuePath()
    {
        $xml = $this->xml->xpath($this->reference);
        $objects = array();
        $i = 0;

        foreach ($xml as $object) {
            foreach ($object as $child) {
                foreach ($this->map as $key => $field) {
                    $objects[$i][$key] = $field->value($child);
                }
                $this->mappedDb($this->map, $this->entity);
                $i++;
            }
        }
        return $objects;
    }

    protected function mappedDb($object, $entity)
    {
        $sqlObject = new MappedSQL($object, $entity);
        $sqlObject->apply();
    }
}