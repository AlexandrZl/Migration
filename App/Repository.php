<?php


class Repository
{
    private $class;
    private $name;
    private $obj;
    private $iterator;
    private $iteratorCount;
    private $values = array();

    public function __construct($class, $name, $obj)
    {
        $this->obj = $obj;
        $this->class = $class;
        $this->name = $name;
        $sqlObj = new TemplatedSQL();
        $sqlObj->createEntity($name);
    }

    public function start()
    {
        $this->newIterator();
        $this->apply();
    }

    private function apply()
    {
        for ($i = 0; $i < $this->iteratorCount; $i++) {
            $repositoryItem = new $this->class($this->iterator->current(), $this->name);
            $this->values[] = $repositoryItem->apply();
            $this->iterator->next();
        }
    }

    private function newIterator()
    {
        $this->iterator = new ArrayIterator($this->obj);
        $this->iteratorCount = $this->iterator->count();
    }

    public function getValues()
    {
        return $this->values;
    }

}