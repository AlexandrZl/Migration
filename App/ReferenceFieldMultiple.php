<?php

class ReferenceFieldMultiple extends Fields
{
    private $reference;
    private $entity;

    public function __construct ($entity, $reference)
    {
        $this->reference = $reference;
        $this->entity = $entity;
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