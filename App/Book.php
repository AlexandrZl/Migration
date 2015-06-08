<?php

class Book extends Table
{
    public function __construct (SimpleXMLElement $obj, $name)
    {
        $this->xmlObj = $obj;
        $this->entity = $name;
        $this->map = array(
            'id' => new PrimaryField('title'),
            'date' => new AggregateField('date'),
            'title' => new StringField('title'),
            'public' => new BoolField('public'),
            'author' => new ReferenceField('Author', 'author'),
        );

        $this->map['date']->setSeparator('/');
    }


}