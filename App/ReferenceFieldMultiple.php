<?php

class ReferenceFieldMultiple extends Fields
{
    private $class;
    private $name;
    private $entity;

    public function __construct ($class, $name, $entity)
    {
        $this->entity = $entity;
        $this->class = $class;
        $this->name = $name;
        $sqlObj = new TemplatedSQL();
        $sqlObj->createEntityRef($name, $entity->entity);
    }

    protected function getValuePath()
    {
        $obj = $this->xmlObj->xpath('.//'.$this->name);

        $repository = new Repository($this->class, $this->name, $obj);
        $repository->start();

        $ids = $repository->getValues();


        $this->fieldName = $this->name;
        $this->fieldValue = $ids;

        $id = $this->entity->primaryField->getId();

        $this->setReference($id, $ids);

        return $ids;
    }

    private function setReference($id, $ids)
    {
        $sql = new TemplatedSQL();
        $result = $sql->setReference($id, $ids, $this->entity->entity, $this->name);
        return $result;
    }
}