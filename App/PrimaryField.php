<?php

class PrimaryField extends Fields
{

    private $md5;
    private $entity;
    private $id;
    protected $salt = "book";
    protected function getValuePath()
    {
        $result = null;

        if ($this->xmlObj) {
            $attr = $this->xmlObj->attributes();

            if ($attr) {
                foreach ($attr as $child => $value) {
                    if ($child == $this->fieldName) {
                        $result = trim($value);
                        break;
                    }
                }
            }

            if (empty($result)) {
                $result = $this->xmlObj->xpath($this->fieldName);
                $result = $result ? $result[0] : null;
            }
        }


        $this->fieldValue = $result;

        return $result;
    }

    public function getObj($entity)
    {
        $this->entity = $entity;
        $this->md5 = $this->createMD5($this->fieldValue);
        $externalId = $this->findExternalId($this->md5, $this->entity);

        if(!$externalId) {
            $this->id = $this->createInternalIdEmpty($this->entity);
            $this->createExternalId($this->md5, $this->id, $this->entity);
        } else {
            $this->id = $externalId['internalId'];
        }

        return $this->id;
    }

    public function getId()
    {
        return $this->id;
    }

    protected function createMD5($value)
    {
        $md5 = null;
        $md5 .= $value;

        $md5 = md5($md5.$this->salt);
        return $md5;
    }

    protected function findExternalId($md5, $name)
    {
        $sql = new TemplatedSQL();
        return $sql->findByExternalId($md5, $name);
    }

    protected function createInternalIdEmpty($name)
    {
        $sql = new TemplatedSQL();
        $result = $sql->emptyInternalId($name);
        return $result;
    }

    protected function createExternalId($md5, $id, $name)
    {
        $sql = new TemplatedSQL();
        $result = $sql->newExternalId($md5, $name, $id);
        return $result;
    }

    protected function findInternalId($id, $name)
    {
        $sql = new TemplatedSQL();
        return $sql->findByInternalId($id, $name);
    }

    protected function createInternalId($id, $name, $obj)
    {
        $sql = new TemplatedSQL();
        $result = $sql->newInternalId($obj, $name, $id);
        return $result;
    }
}