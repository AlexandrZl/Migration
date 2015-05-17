<?php

class Author extends Table
{
    public function __construct (SimpleXMLElement $obj)
    {
        $this->xml = $obj;
        $this->map = array(
            'id' => new PrimaryField('firstName'),
            'firstName' => new StringField('firstName'),
            'lastName' => new StringField('lastName'),
        );
    }

    public function apply()
    {
        $sqlObject = new MappedSQL($this->map, 'author');
        return $sqlObject->apply();
    }

    public function applyEntity()
    {
//        $sqlObject = new MappedSQL($this->map, 'book');
//        return $sqlObject->applyEntity();
    }
}