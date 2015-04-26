<?php
class MappedSQL implements iFields
{
    protected $object;
    protected $entity;
    protected $salt = "book";
    protected $md5;

    public function __construct ($obj, $entity)
    {
        $this->object = $obj;
        $this->entity = $entity;
    }

    public function apply()
    {
        foreach ($this->object as $field) {
            switch(true) {
                case $field instanceof PrimaryField:
                    $this->createMD5();
                    if (!$this->findExternalId()) {
                        $newInId = $this->createInternalId();
                        if($newInId && $newInId['id']) {
                            $this->createExternalId($newInId['id']);
                        }
                    }
                    break;
            }
        }
        return $this->md5;
    }

    protected function createMD5()
    {
        $i = 0;
        foreach ($this->object as $key => $field) {
            if($i > 1) break;
            switch(true) {
                case $field instanceof StringField:
                    $this->md5 .= $field->getValue();
                    $i++;
                    break;
            }
        }
        $this->md5 = md5($this->md5.$this->salt);
    }

    protected function findExternalId()
    {
        $sql = new TemplatedSQL();
        return $sql->findByExternalId($this->md5, $this->entity);

    }

    protected function createInternalId()
    {
        $sql = new TemplatedSQL();
        $result = $sql->newInternalId($this->object, $this->entity);
        return $result;
    }

    protected function createExternalId($id)
    {
        $sql = new TemplatedSQL();
        $result = $sql->newExternalId($this->md5, $id, $this->entity);
        return $result;
    }



}
?>
