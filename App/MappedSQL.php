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
                    if(!$this->findExternalId()){
                        $this->createExternalId();
                        $this->id = $this->createInternalId();
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

    protected function createExternalId()
    {
        $sql = new TemplatedSQL();
        $result = $sql->newExternalId($this->md5, $this->entity);
        return $result;
    }

    protected function createInternalId()
    {
        $sql = new TemplatedSQL();
        $result = $sql->newInternalId($this->object, $this->entity);
        return $result;
    }
}
?>
