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
            'date' => new DateField('date'),
            'title' => new StringField('title'),
            'public' => new BoolField('public'),
            'authors' => new ReferenceFieldMultiple('author', 'authors'),
        );

        $this->map['date']->setFields(array(
            'day' => new NumericField('day'),
            'month' => new NumericField('month'),
            'year' => new NumericField('year'),
        ));

        $this->map['authors']->setFields(array(
            'id' => new PrimaryField('id'),
            'firstName' => new StringField('firstName'),
            'lastName' => new StringField('lastName'),
        ));
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