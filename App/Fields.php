<?php
abstract class Fields
{
    abstract protected function getValuePath();
    protected $xmlObj;
    protected $fieldName;
    protected $fieldValue;

    public function __construct ($name)
    {
        $this->fieldName = $name;
    }

    public function getName()
    {
        return $this->fieldName;
    }

    public function getValue()
    {
        return $this->fieldValue;
    }

    public function value($obj)
    {
        $this->xmlObj = $obj;
        return $this->getValuePath();
    }

    public function getObj()
    {
        return $this->getValue();
    }
}