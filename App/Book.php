<?php

class Book
{
    private $xml;
    private $reference;
//    private $map = array(
//        'id' => 'bid',
//        'title' => 'booktitle',
//    );
    private $map = array();

    public function __construct (SimpleXMLElement $obj)
    {
//        $this->primary = new PrimaaryField('id');
//        $this->map = array(
//            'title' => new StringField('title'),
//            'authors' => new ReferenceFieldMultiple('Author', 'authors'), //Author - table(entity) authors(xpath)
//        );

        $this->xml = $obj;

        $this->map = array(
            'id' => new PrimaryField('id'),
            'title' => new StringField('title'),
            'author' => new ReferenceFieldMultiple('Author', 'author'),
        );

        echo $this->map['id']->value($this->xml);
        echo $this->map['title']->value($this->xml);
        echo $this->map['author']->value($this->xml);


//        $this->map = array(
//            'id' => 'bid',
//            'title' => 'booktitle'
//        );

        /// $object = new Object();
        //  $object->id = $this->primary->value($this->xml);
        // foreach ($this->map as $key => &$field) {
        //    $object->$key = $field->value($this->xml);
        //}
        // {
        //   id: '100',
        //    name: 'war_and_peace',
        //    authors: [10, 156]
        //}

    }

    public function __get($table)
    {
        $key = array_search($table, $this->map);
        $result = NULL;

        if ($key) {
            $result = $this->xml->attributes()->{$key};

            if (!$result)
            {
                $result = $this->xml->xpath($key)[0];
            }
        }

        return $result;
    }

    private function value()
    {
        
    }

    private function apply()
    {

    }

    private function createMap()
    {
        if ($this->xml) {
            $attr = $this->xml->attributes();

            foreach ($this->xml as $child => $value) {
                $value = trim($value);
                if (!empty($value)) {
                    $fields['name'][] = $child;
//                    $fields['value'][] = $value;
                    $fields['type'][] = $this->getType($value);
                }
            }

            foreach ($attr as $child => $val) {
                $child = trim($child);
                if (!empty($child)) {
                    $fields['name'][] = $child;
//                    $fields['value'][] = $val;
                    $fields['type'][] = $this->getType($val);
                }
            }
        }

        if ($this->reference) {
            foreach ($this->reference as $obj) {
                foreach ($obj as $child) {
                    $attr = $child->attributes();
                    foreach ($attr as $key => $val) {
                        $key = trim($key);
                        if (!empty($key)) {
                            $referenceFields['name'][] = $key;
//                            $referenceFields['value'][] = $val;
                            $referenceFields['type'][] = $this->getType($val);
                        }
                    }
                    foreach ($child as $key => $value) {
                        $referenceFields['name'][] = $key;
//                        $referenceFields['value'][] = $value;
                        $referenceFields['type'][] = $this->getType($value);
                    }
                    break;
                }
            }
        }
    }

    private function getType($type)
    {
        $result = '';
        $type = (string) $type;
        $result = is_numeric($type);
        if (!$result) {
            $result = gettype($type);
        } else {
            if ((int) $type == (float) $type) {
                $result = 'integer';
            } else {
                $result = 'double';
            }
        }
        return $result;
    }
}


//private $map = array(
//    'id' => new PrimaaryField('bid'),
//    'title' => new StringField('title'),
//    'author' => new ReferenceField('Author', 'author'),
//);
