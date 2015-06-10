<?php

class AggregateField extends Fields
{
    private $separator;
    private $number;
    private $mark;

    public function setSeparator($mark)
    {
        $this->separator = $mark;
    }

    public function select($number, $mark)
    {
        $this->number = $number;
        $this->mark = $mark;
    }

    protected function getValuePath()
    {

        $result = null;
        $xml = $this->xmlObj->xpath($this->getName());
        if ($this->separator) {
            $date = '';

            foreach ($xml as $object) {
                foreach ($object as $field) {
                    $date .= $field.$this->separator;
                }
            }
            $date = substr($date, 0, -1);
            $this->fieldValue = $date;
        } else if (isset($this->number) && isset($this->mark)) {
            foreach ($xml as $key => $value) {
                $result = explode($this->mark, $value);
                $this->fieldValue = $result[$this->number] ? $result[$this->number] : null;
            }
        }


        return $this->fieldValue;
    }
}