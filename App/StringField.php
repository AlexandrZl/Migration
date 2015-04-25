<?php

class StringField extends Fields
{
    protected function getValuePath()
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

        $this->fieldValue = $result;

        return $result;
    }
}