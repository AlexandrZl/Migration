<?php
class TemplatedSQL
{
    private $pdo;

    const INT = 'int(11)',
          VARCHAR = 'VARCHAR( 250 )';

    public function __construct (PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function getType($type)
    {
        $result = '';
        $type = (string) $type;
        $result = is_numeric($type);
        if (!$result) {
            $result = gettype($type);
        } else {
            if ((int) $type == (float) $type) {
                $result = 'integer';
            } else {
                $result = 'double';
            }
        }
        return $result;
    }

    private function QueryInsert ($table, $fields)
    {
        $sql = "INSERT INTO $table (";

        foreach($fields as $field => $type)
        {
            if ($type->count() == 0) {
                $sql .= "$field,";
            }
        }
        foreach($fields->attributes() as $field => $name)
        {
                $sql .= " $field,";
        }

        $sql = substr($sql, 0, -1);
        $sql.= ") values (";

        foreach($fields as $field => $type)
        {
            if ($type->count() == 0) {
                $sql .= "'$type',";
            }
        }
        foreach($fields->attributes() as $field => $name)
        {
            $sql .= " $name,";
        }
        $sql = substr($sql, 0, -1);

        $sql .= ")";

        $this->pdo->exec($sql);
    }

    private function QueryCreateTable ($table, $fields)
    {
        $sql ="CREATE TABLE IF NOT EXISTS $table ( ";

        $i = 0;
        foreach ($fields['name'] as $value) {
            if (end($fields['name']) == $value) {
                    if ($fields['type'][$i] == 'string') {
                        $sql.=$value." ".self::VARCHAR." NOT NULL);";
                    } else if ($fields['type'][$i] == 'integer') {
                        $sql.=$value." ".self::INT." NOT NULL);";
                    } else if ($fields['type'][$i] == 'double') {
                        $sql.=$value." ".self::INT." NOT NULL);";
                    }
            } else {
                if ($value == 'id') {
                    $sql.=$value." int(11) AUTO_INCREMENT PRIMARY KEY,";
                } else {
                    if ($fields['type'][$i] == 'string') {
                        $sql.=$value." ".self::VARCHAR." NOT NULL,";
                    } else if ($fields['type'][$i] == 'integer') {
                        $sql.=$value." ".self::INT." NOT NULL,";
                    } else if ($fields['type'][$i] == 'double') {
                        $sql.=$value." ".self::INT." NOT NULL,";
                    }
                }
            }
            $i++;
        }
        $this->pdo->exec($sql);
    }

    public function execute ($data)
    {
        $table = "books";
        $fields = array();

        foreach ($data as $child) {
            $attr = $child->attributes();

            foreach ($attr as $key => $value) {
                $fields['name'][] = $key;
                $fields['type'][] = $this->getType($value);
            }
            foreach ($child as $val => $key) {
                $fields['name'][] = $val;
                $fields['type'][] = $this->getType($key);
            }
            break;
        }

        $this->QueryCreateTable($table, $fields);

        foreach ($data as $value) {
            $this->QueryInsert($table, $value);
        }
    }

}
?>
