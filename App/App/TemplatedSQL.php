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
                $sql .= " $field,";
            }
        }
        $sql = substr($sql, 0, -1);
        $sql.= ") values (";

        foreach($fields as $field => $type)
        {
            if ($type->count() == 0) {
                $sql .= "'$type',";
            }
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
        $table = $data->getName();
        $fields = array();

        foreach ($data->children()->children() as $child)
        {
            if ($child->count() == 0) {
                $fields['name'][] = $child->getName();
                $fields['type'][] = $this->getType($child->__toString());
            }
        }

        $this->QueryCreateTable($table, $fields);

        foreach ($data as $value) {
            $this->QueryInsert($table, $value);
        }
    }

}
?>
