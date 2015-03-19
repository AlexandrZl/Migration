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
        // $p = xml_parser_create();
        // xml_parse_into_struct($p, $contents, $vals, $index);
        // xml_parser_free($p);

        return new SimpleXMLElement($xmlString);
    }
}
?>
