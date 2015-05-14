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
                    $md5 = $this->createMD5();
                    $this->id = $this->createInternalId($key);
                    break;
            }
        }
        return $this->id;
    }

    protected function findExternalId($field)
    {
        $sql = new TemplatedSQL();
        return $sql->findByExternalId($field, $this->id['id']);
    }

    protected function createInternalId($key)
    {
        $sql = new TemplatedSQL();
        $result = $sql->newInternalId($this->object, $this->entity, $this->md5, $key);
        return $result;
    }

    protected function createExternalId($field)
    {
        $sql = new TemplatedSQL();
        $result = $sql->newExternalId($field, $this->id['id']);
        return $result;
    }

    public static function createMap($entity, $reference)
    {
        $sqlObj = new TemplatedSQL();
        return $sqlObj->createMap($entity, $reference);
    }

    protected function createMD5()
    {
        $i = 0;
        foreach ($this->object as $key => $field) {
            if($i > 2) break;
            switch(true) {
                case $field instanceof PrimaryField:
                    $this->md5 .= $field->getValue();
                    $i++;
                    break;
                case $field instanceof StringField:
                    $this->md5 .= $field->getValue();
                    $i++;
                    break;
//                case default:
//                    $i++;
//                    break;
            }
        }
        $this->md5 = md5($this->md5.$this->salt);

        return $this->md5;
    }
}
?>
