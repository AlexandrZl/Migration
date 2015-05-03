<?php
class MappedSQL
{
    protected $object;
    protected $entity;
    protected $salt = "book";
    protected $md5;
    private $added = 0;

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
                    $extId = $this->findExternalId();
                    if (!$extId) {
                        $newInId = $this->createInternalId();
                        if(isset($newInId) && isset($newInId['id'])) {
                            $this->createExternalId($newInId['id']);
                            $this->added ++;
                        } else {
                            $this->createExternalId($newInId['existId']);
                        }
                    } else {
                        $internalId = $this->findInternalId($extId['internalId'], $extId['type']);
                        if (!$internalId) {
                            $this->createInternalId($extId['internalId']);
                        }
                    }
                    break;
            }
        }
        return $this->added;
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

    protected function findInternalId($id, $type)
    {
        $sql = new TemplatedSQL();
        return $sql->findByInternalField('id', $id, $type);
    }

    protected function createInternalId($id = null)
    {
        $sql = new TemplatedSQL();
        $result = $sql->newInternalId($this->object, $this->entity, $id);
        return $result;
    }

    protected function createExternalId($id)
    {
        $sql = new TemplatedSQL();
        $result = $sql->newExternalId($this->md5, $id, $this->entity);
        return $result;
    }

    public static function createMap()
    {
        $sqlObj = new TemplatedSQL();
        $sqlObj->createMap();
    }
}
?>
