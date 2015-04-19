<?php

class StringField extends Fields
{
    private $fieldName;

    public function __construct ($name)
    {
        $this->fieldName = $name;
    }

    protected function getValue()
    {
        $result = null;

        if ($this->xml) {
            $attr = $this->xml->attributes();

            if ($attr) {
                foreach ($attr as $child => $value) {
                    if ($child == $this->fieldName) {
                        $result = trim($value);
                        break;
                    }
                }
            }

            if (empty($result)) {
                $result = $this->xml->xpath($this->fieldName);
                $result = $result ? $result[0] : null;
            }
        }

        return $result;
    }
}