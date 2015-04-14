<?php

class Book
{
    private $book;
    private $reference;
    private $map = array(
        'id' => 'bid',
        'title' => 'booktitle',
    );

    public function __construct ($obj, $reference)
    {
        if ($obj) {
            $this->book = $obj;
        }
        if ($reference) {
            $this->reference = $this->book->xpath($reference);
        }
        $this->createMap();
    }

    public function __get($table)
    {
        $key = array_search($table, $this->map);
        $result = NULL;

        if ($key) {
            $result = $this->book->attributes()->{$key};

            if (!$result)
            {
                $result = $this->book->xpath($key)[0];
            }
        }

        return $result;
    }

    private function createMap()
    {
        if ($this->book) {
            $attr = $this->book->attributes();

            foreach ($this->book as $child => $value) {
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
