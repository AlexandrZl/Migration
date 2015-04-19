<?php

class Book
{
    private $xml;
    private $map = array();

    public function __construct (SimpleXMLElement $obj)
    {
        $this->xml = $obj;
        $this->map = array(
            'id' => new PrimaryField('id'),
            'title' => new StringField('title'),
            'authors' => new ReferenceFieldMultiple('Author', 'authors'),
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

        return $object;

    }
}