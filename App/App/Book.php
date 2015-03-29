<?php

class Book
{
    private $book;
    private $map = array(
        'id' => 'bid',
        'title' => 'booktitle'
    );

    public function __construct ($obj)
    {
        $this->book = $obj;
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
}