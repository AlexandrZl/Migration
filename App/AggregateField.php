<?php

class AggregateField extends Fields
{
    private $separator;

    public function setSeparator($mark)
    {
        $this->separator = $mark;
    }

    protected function getValuePath()
    {
        $xml = $this->xmlObj->xpath($this->getName());
        $date = '';

        foreach ($xml as $object) {
            foreach ($object as $field) {
                $date .= $field.$this->separator;
            }
        }
        $date = substr($date, 0, -1);
        $this->fieldValue = $date;

        return $this->fieldValue;
    }
}