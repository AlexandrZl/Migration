<?php

class Author extends Table
{
    public function __construct (SimpleXMLElement $obj, $name)
    {
        $this->xmlObj = $obj;
        $this->entity = $name;
        $this->map = array(
            'id' => new PrimaryField('firstName'),
            'firstName' => new StringField('firstName'),
            'lastName' => new StringField('lastName'),
        );
    }

}