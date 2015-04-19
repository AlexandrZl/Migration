<?php
abstract class Fields
{
    abstract protected function getValue();
    protected $xml;

    public function value($xml)
    {
        $this->xml = $xml;
        return $this->getValue();
    }
}