<?php

class Book extends Table
{
    public function __construct (SimpleXMLElement $obj)
    {
        $this->xml = $obj;
        $this->map = array(
            'id' => new PrimaryField('id'),
            'date' => new AggregateField('date'),
            'title' => new StringField('title'),
            'public' => new BoolField('public'),
            'authors' => new ReferenceFieldMultiple('author', 'authors', 'book'),
        );

        $this->map['date']->setSeparator('/');
    }

    public function apply()
    {
        $sqlObject = new MappedSQL($this->map, 'book');
        return $sqlObject->apply();
    }
}