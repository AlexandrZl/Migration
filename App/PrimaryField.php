<?php

class PrimaryField extends Fields
{

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
}