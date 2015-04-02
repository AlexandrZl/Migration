<?php
class XMLParser
{
    public function parseFile ($filePath)
    {
        if (file_exists($filePath)) {
            $contents = file_get_contents($filePath);
            if (!$contents) {
                throw new Exception("Cannot read file: $filePath");
            }
        } else {
            throw new Exception("Cannot open file: $filePath");
        }
        $parsed = $this->parseXML($contents);
        if (!$parsed) {
            throw new Exception("XML decoding of '$filePath' failed");
        }
        
        return $parsed;
    }
    
    public function parseXML ($xmlString)
    {
        return new SimpleXMLElement($xmlString);
    }
}
?>
