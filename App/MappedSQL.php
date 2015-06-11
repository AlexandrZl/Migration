<?php
class MappedSQL
{
    protected $object;
    protected $entity;
    private $id;
    protected $md5;
    protected $salt = "book";

    public function __construct ($obj, $entity)
    {
        $this->object = $obj;
        $this->entity = $entity;
    }

    public function apply()
    {
        foreach ($this->object as $key => $field) {
            switch(true) {
                case $field instanceof PrimaryField:
                    $this->createMD5();
                    $externalId = $this->findExternalId();
                    if(!$externalId){
                        $this->id = $this->createInternalId();
                        $this->createExternalId();
                    } else {
                        $internalId = $externalId['internalId'];
                        if(!$this->findInternalId($internalId)) {
                            $this->createInternalId($internalId);
                        } else {
                            if($this->isReferenceField()){
                                if ($this->isMock($internalId)){
                                    $this->setEntity($internalId);
                                }
                            }
                            if($this->isReferenceFieldMultiple()){
                                $this->setEntity($internalId);
                                $reference = $this->getReferenceFieldMultiple();
                                $this->setReference($internalId, $reference->getValue(), $reference->getName());
                            }
                            $this->id = $internalId;
                        }
                    }
                    break;
            }
        }
        return $this->id;
    }


    private function setReference($id, $ids, $referName)
    {
        $sql = new TemplatedSQL();
        $result = $sql->setReference($id, $ids, $this->entity, $referName);
        return $result;
    }

    public function isReferenceFieldMultiple()
    {
        $result = false;
        foreach ($this->object as $key => $field) {
            if ($field instanceof ReferenceFieldMultiple) {
                $result = true;
            }
        }
        return $result;
    }

    public function getReferenceFieldMultiple()
    {
        $result = false;
        foreach ($this->object as $key => $field) {
            if ($field instanceof ReferenceFieldMultiple) {
                $result = $field;
                break;
            }
        }
        return $result;
    }

    public function isReferenceField()
    {
        $result = false;
        foreach ($this->object as $key => $field) {
            if ($field instanceof ReferenceField) {
                $result = true;
            }
        }
        return $result;
    }

    public function emptyEntity()
    {
        foreach ($this->object as $key => $field) {
            switch(true) {
                case $field instanceof PrimaryField:
                    $this->createMD5();
                    $externalId = $this->findExternalId();
                    if(!$externalId){
                        $this->id = $this->createInternalIdEmpty();
                        $this->id = $this->createExternalId();
                    } else {
                        $this->id = $externalId['internalId'];
                    }
                    break;
            }
        }
        return $this->id;
    }

    protected function isMock($id)
    {
        $sql = new TemplatedSQL();
        $result = $sql->isMock($id, $this->entity);
        return $result;
    }

    protected function setEntity($id)
    {
        $sql = new TemplatedSQL();
        $result = $sql->setEntity($id, $this->object, $this->entity);
        return $result;
    }

    protected function createMD5()
    {
        foreach ($this->object as $key => $field) {
            if ($field instanceof PrimaryField) {
                $this->md5 .= $field->getValue();
                break;
            }
        }

        $this->md5 = md5($this->md5.$this->salt);
        return $this->md5;
    }

    protected function findExternalId()
    {
        $sql = new TemplatedSQL();
        return $sql->findByExternalId($this->md5, $this->entity);
    }

    protected function findInternalId($id)
    {
        $sql = new TemplatedSQL();
        return $sql->findByInternalId($id, $this->entity);
    }

    protected function createExternalId()
    {
        $sql = new TemplatedSQL();
        $result = $sql->newExternalId($this->md5, $this->entity, $this->id);
        return $result;
    }

    protected function createInternalIdEmpty()
    {
        $sql = new TemplatedSQL();
        $result = $sql->emptyInternalId($this->object, $this->entity);
        return $result;
    }

    protected function createInternalId($id = null)
    {
        $sql = new TemplatedSQL();
        $result = $sql->newInternalId($this->object, $this->entity, $id);
        return $result;
    }
}
?>
