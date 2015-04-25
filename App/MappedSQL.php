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
                    $this->createExternalId($this->object);
                    $this->findExternalId();
                    break;
            }
        }
    }


    protected function createExternalId($obj)
    {
        $i = 0;
        foreach ($obj as $key => $field) {
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
        global $PDO;
        $sql = new TemplatedSQL($PDO);
        return $sql->findById($this->md5, $this->entity);

    }


}
?>
