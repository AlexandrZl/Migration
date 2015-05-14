<?php
class TemplatedSQL
{
    private $pdo;

    public function __construct ()
    {
        global $PDO;
        $this->pdo = $PDO;
    }

    public function findByExternalId($field, $id)
    {
        $fields = explode("_", $field->getCreatedEntity());
        $table = $field->getCreatedEntity();
        foreach ($field->getIds() as $reference_id) {
            $q = $this->pdo->prepare("SELECT * FROM $table WHERE $fields[0] = :id and $fields[1] = :reference_id limit 1");
            $q->bindValue(':id', $id);
            $q->bindValue(':reference_id', $reference_id);
            $q->execute();

            try {
                $q->execute();
                $result = $q->fetch(PDO::FETCH_ASSOC);
            }
            catch (Exception $e) {
                $result = false;
            }
            if($result) return $result;
        }

        return $result;
    }

    public function newExternalId($field, $id)
    {
        $fields = explode("_", $field->getCreatedEntity());
        $table = $field->getCreatedEntity();
        foreach ($field->getIds() as $reference_id) {
            $sql = "INSERT INTO $table ($fields[0], $fields[1]) VALUES ('$id', '$reference_id')";

            try {
                $this->pdo->exec($sql);
                $result = true;
            }
            catch (Exception $e) {
                $result = false;
            }
        }

        return $result;
    }


    public function newInternalId($obj, $entity, $md5, $key)
    {
        $primField = array();
        $id = null;

        $sql = "INSERT INTO $entity (";

        foreach ($obj as $field) {
            if ($field instanceof PrimaryField) {
                $primField[$field->getName()] = $md5;
                $sql .= $key.", ";
            } else if ($field instanceof ReferenceFieldMultiple) {
                continue;
            } else {
                $sql .= $field->getName().", ";
            }

        }

        $sql = substr($sql, 0, -2);
        $sql .= ") VALUES (";

        foreach ($obj as $field) {
            if ($field instanceof PrimaryField) {
                $sql .= "'".$md5."', ";
            }
            else if ($field instanceof ReferenceFieldMultiple) {
                continue;
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
            $result['id'] = filter_var($e->errorInfo[2], FILTER_SANITIZE_NUMBER_INT);
        }
        return $result;
    }

    public function createMap($entity, $reference)
    {
        $table = $reference."_".$entity;
        try {
            $sql ="CREATE table $table(
             $reference VARCHAR ( 50 ) NOT NULL,
             $entity VARCHAR ( 50 ) NOT NULL,
             FOREIGN KEY (`$reference`) REFERENCES `$reference` (`id`),
             FOREIGN KEY (`$entity`) REFERENCES `$entity` (`id`));";
            $this->pdo->exec($sql);
            CLIMessage::show("Created $table Table", "success");
            return $table;
        } catch(PDOException $e) {
            return false;
        }
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
}
?>