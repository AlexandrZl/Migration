<?php
class MappedSQL
{
    protected $object;
    protected $entity;
    private $id;

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
                    $this->id = $this->createInternalId();
                    break;
                case $field instanceof ReferenceFieldMultiple:
                    if (!$this->findExternalId($field)) {
                        $this->createExternalId($field);
                    }
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

    protected function createInternalId($id = null)
    {
        $sql = new TemplatedSQL();
        $result = $sql->newInternalId($this->object, $this->entity, $id);
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
}
?>
