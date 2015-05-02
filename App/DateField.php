<?php

class DateField extends Fields
{
    private $map = array();

    public function setFields($fields)
    {
        foreach ($fields as $key => $field) {
            $this->map[$key] = $field;
        }
    }

    protected function getValuePath()
    {
        $xml = $this->xml->xpath($this->getName());
        $date = '';

        foreach ($xml as $object) {
            foreach ($this->map as $key => $field) {
                $date .= $field->value($object)."/";
            }
        }
        $date = substr($date, 0, -1);
        $this->fieldValue = $date;

        return $this->fieldValue;
    }
}