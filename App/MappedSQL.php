<?php
class MappedSQL
{
    protected $object;
    protected $entity;
    private $id;
    protected $md5;
    protected $salt = "book";
    protected $objects = array();

    public function __construct($obj, $entity)
    {
        $this->object = $obj;
        $this->entity = $entity;
    }

    public function apply($id)
    {
        $this->objects['id'] =  $id;
        foreach ($this->object as $key => $field) {
            $this->objects[$key] = $field->getObj($this->entity);
        }
        $result = $this->save();
        return $result;
    }

    private function save()
    {
        $sql = new TemplatedSQL();
        $result = $sql->save($this->objects, $this->entity);
        return $result;
    }
}
?>
