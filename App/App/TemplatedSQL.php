<?php
class TemplatedSQL
{
    private $pdo;

    public function __construct (PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function QueryInsert ($table, $fields)
    {
        $sql = "INSERT INTO $table (";

        foreach($fields as $field => $type)
        {
            if (end($fields) == $type) {
                $sql.= " $field";
            } else {
                $sql.= " $field,";
            }
        }

        $sql.= ") values (";

        foreach($fields as $field => $type)
        {
            if (end($fields) == $type) {
                $sql.= "'$type' ";
            } else {
                $sql.= "'$type', ";
            }
        }

        $sql .= ")";

        $this->pdo->exec($sql);
    }

    private function QueryCreateTable ($table, $fields)
    {
        $sql ="CREATE TABLE IF NOT EXISTS $table ( ";

        foreach ($fields as $value) {
            if (end($fields) == $value) {
                    $sql.=$value." VARCHAR( 250 ) NOT NULL);";
            } else {
                if ($value == 'id') {
                    $sql.=$value." int(11) AUTO_INCREMENT PRIMARY KEY,";
                } else {
                    $sql.=$value." VARCHAR( 250 ) NOT NULL,";
                }
            }
        }
        $this->pdo->exec($sql);
    }

    public function execute ($data)
    {
        $table = $data->getName();
        $fields = array();

        foreach ($data->children()->children() as $child)
        {
            $fields[] = $child->getName();
        }

        $this->QueryCreateTable($table, $fields);

        foreach ($data as $value) {
            $this->QueryInsert($table, $value);
        }
    }

}
?>
