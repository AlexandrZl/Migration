<?php

class Book
{
    private $xml;
    private $map = array();
    private $object = array();

    public function __construct (SimpleXMLElement $obj)
    {
        $this->xml = $obj;
        $this->map = array(
            'id' => new PrimaryField('id'),
            'title' => new StringField('title'),
            'authors' => new ReferenceFieldMultiple('author', 'authors'),
        );
    }

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
        $sqlObject = new MappedSQL($this->map, 'book');
        return $sqlObject->apply();
    }
}