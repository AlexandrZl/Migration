<?php
abstract class Fields
{
    abstract protected function getValuePath();
    protected $xml;
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

    public function value($xml)
    {
        $this->xml = $xml;
        return $this->getValuePath();
    }
}