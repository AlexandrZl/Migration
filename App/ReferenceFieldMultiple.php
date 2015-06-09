<?php

class ReferenceFieldMultiple extends Fields
{
    private $class;
    private $name;

    public function __construct ($class, $name, $entity)
    {
        $this->class = $class;
        $this->name = $name;
        $sqlObj = new TemplatedSQL();
        $sqlObj->createEntityRef($name, $entity);
    }

    protected function getValuePath()
    {
        $obj = $this->xmlObj->xpath('.//'.$this->name);

        $repository = new Repository($this->class, $this->name, $obj);
        $repository->start();

        $ids = $repository->getValues();

        $this->fieldName = $this->name;
        $this->fieldValue = $ids;

        return $ids;
    }
}