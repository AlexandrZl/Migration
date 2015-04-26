<?php
class TemplatedSQL
{
    private $pdo;

    const INT = 'int(11)',
          VARCHAR = 'VARCHAR( 250 )';

    public function __construct ()
    {
        global $PDO;
        $this->pdo = $PDO;
    }

    public function findByExternalId($id, $type)
    {
        $result = false;

        $q = $this->pdo->prepare("SELECT * FROM mappedDB WHERE externalId = :id and type = :type limit 1");
        $q->bindValue(':id', $id);
        $q->bindValue(':type', $type);
        $q->execute();

        if($q->fetch(PDO::FETCH_ASSOC)) $result = true;

        return $result;
    }

    public function findByInternalField($nameField, $valueField, $table)
    {
        $q = $this->pdo->prepare("SELECT * FROM $table WHERE $nameField = '$valueField' limit 1");
        $q->execute();

        try {
            $result = $q->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    public function newInternalId($obj, $entity)
    {
        $primField = array();

        $sql = "INSERT INTO $entity (";

        foreach ($obj as $field) {
            if ($field instanceof PrimaryField) {
                $primField[$field->getName()] = $field->getValue();
            }
            if ($field instanceof ReferenceFieldMultiple) {
                $sql .=$field->getReference() ;
            }
            $sql .= $field->getName().", ";
        }

        $sql = substr($sql, 0, -2);
        $sql .= ") VALUES (";

        foreach ($obj as $field) {
            if ($field instanceof ReferenceFieldMultiple) {
                $sql .="'";
                $ids = $field->getIds();
                foreach ($ids as $value) {
                    $sql .=$value.",";
                }
                $sql = substr($sql, 0, -1);
                $sql .="'  ";
            } else {
                $sql .= "'".$field->getValue()."', ";
            }
        }
        $sql = substr($sql, 0, -2);
        $sql .= ")";

        try {
            $this->pdo->exec($sql);
            foreach ($primField as $key => $value) {
                $result = $this->findByInternalField($key, $value, $entity);
                break;
            }
        }
        catch (Exception $e) {
            $result = false;
        }
        return $result;
    }

    public function newExternalId($externalId, $internalId, $entity)
    {
        $sql = "INSERT INTO mappedDB (externalId, internalId, type) VALUES ('$externalId', '$internalId', '$entity')";

        try {
            $this->pdo->exec($sql);
            $result = true;
        }
        catch (Exception $e) {
            $result = false;
        }
        return $result;
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
        $arrayFields = array();

        $sqlTable = "INSERT INTO $table (";
        $sqlValue = ") values (";

        foreach($fields->children() as $field => $value)
        {
            if (!in_array($field, $arrayFields)) {
                $sqlTable .= "$field,";
                $sqlValue .= "'$value',";
            }
            $arrayFields[] = $field;
        }

        foreach($fields->attributes() as $field => $value)
        {
            if (!in_array($field, $arrayFields)) {
                $sqlTable .= " $field,";
                $sqlValue .= "'$value',";
            }
            $arrayFields[] = $field;
        }

        $sqlTable = substr($sqlTable, 0, -1);
        $sqlValue = substr($sqlValue, 0, -1);

        $sqlValue .= ")";

        $sql = $sqlTable.$sqlValue;

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

    public function execute ($data, $tableName)
    {
        $table = $tableName;
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
