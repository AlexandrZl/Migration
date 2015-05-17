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

    public function applyEntity()
    {
        $id = null;
        $ids = null;

        foreach ($this->object as $key => $field) {
            switch(true) {
                case $field instanceof PrimaryField:
                    $id = $this->createMD5();
                    $nameField = $field->getName();
                    break;
                case $field instanceof ReferenceFieldMultiple:
                    $ids = $field->getObjects();
                    $entity = $field->getEntity();
                    break;
            }

            if ($id && $ids) {
                $this->findExternalId($id, $nameField);
                foreach ($ids as $referId ) {
                    $result = $this->findReference($referId, $entity);
                    if ($result) {
                        $entityId = $this->findEntityId($field->getCreatedEntity(), $this->entity, $entity, $id, $result['id']);
                        if (!$entityId) {
                            $newEntityId = $this->createEntityId($field->getCreatedEntity(), $this->entity, $entity, $id, $result['id']);
                        }
                    }
                }
            }
        }
        return $this->id;
    }

    protected function findReference ($obj, $entity)
    {
        $md5 = $this->md5Referense($obj);
        if ($md5) {
            $sql = new TemplatedSQL();
            $id = $sql->findByReferenceId($md5, $entity);
            return $id;
        }
        return false;
    }

    protected function findEntityId ($table, $field, $referField, $id, $referId)
    {
        $sql = new TemplatedSQL();
        return $sql->findEntityId($table, $field, $referField, $id, $referId);
    }

    protected function createEntityId ($table, $field, $referField, $id, $referId)
    {
        $sql = new TemplatedSQL();
        return $sql->createEntityId($table, $field, $referField, $id, $referId);
    }

    protected function findExternalId($id, $nameField)
    {
        $sql = new TemplatedSQL();
        return $sql->findByExternalId($id, $nameField, $this->entity);
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
            if ($field instanceof PrimaryField) {
                continue;
            } else {
                $this->md5 .= $field->getValue();
            }
            $i++;
//            switch(true) {
//                case $field instanceof PrimaryField:
//                    $this->md5 .= $field->getValue();
//                    $i++;
//                    break;
//                case $field instanceof StringField:
//                    $this->md5 .= $field->getValue();
//                    $i++;
//                    break;
////                case default:
////                    $i++;
////                    break;
//            }
        }
        $this->md5 = md5($this->md5.$this->salt);

        return $this->md5;
    }

    protected function md5Referense($obj)
    {
        $result = null;
        $i = 0;

        foreach ($obj as $field) {
            if($i > 2) break;
            $result .= $field;
            $i++;
        }
        $result = md5($result.$this->salt);

        return $result;
    }
}
?>
