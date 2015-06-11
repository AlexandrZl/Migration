<?php

class Article extends Table
{
    public function __construct (SimpleXMLElement $obj, $name)
    {
        $this->xmlObj = $obj;
        $this->entity = $name;
        $this->map = array(
            'id' => new PrimaryField('title'),
            'title' => new StringField('title'),
        );
    }

}