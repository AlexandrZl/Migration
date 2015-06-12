<?php

class Book extends Table
{
    public function __construct (SimpleXMLElement $obj, $name)
    {
        $this->xmlObj = $obj;
        $this->entity = $name;
        $this->primaryField = new PrimaryField('title');
        $this->map = array(
            'date' => new AggregateField('date'),
            'title' => new StringField('title'),
            'public' => new BoolField('public'),
//            'author' => new ReferenceField('Author', 'author'),
            'author' => new ReferenceFieldMultiple('Author', 'author', $this),
        );

        $this->map['date']->setSeparator('/');
    }


}