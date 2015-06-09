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
        if ($this->entity == 'book') {
            var_dump($this->object);die;
        }
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
                            $this->id = $internalId;
                        }
                    }
                    break;
            }
        }
        return $this->id;
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

    protected function createInternalId($id = null)
    {
        $sql = new TemplatedSQL();
        $result = $sql->newInternalId($this->object, $this->entity, $id);
        return $result;
    }
}
?>
