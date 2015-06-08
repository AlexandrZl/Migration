<?php
class TemplatedSQL
{
    private $pdo;

    public function __construct ()
    {
        global $PDO;
        $this->pdo = $PDO;
    }

    public function findByExternalId($id, $nameField)
    {
        $table = 'entity_'.$nameField;

        $q = $this->pdo->prepare("SELECT * FROM $table WHERE `externalId` = :id limit 1");
        $q->bindValue(':id', $id);
        $q->execute();

        try {
            $q->execute();
            $result = $q->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    public function createEntity($name)
    {
        $table = 'entity_'.$name;
        try {
            $sql ="CREATE table $table(
             `externalId` VARCHAR ( 50 ) NOT NULL);";
            $this->pdo->exec($sql);
            CLIMessage::show("Created $name Table", "success");
            return $name;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function newExternalId($id, $nameField)
    {
        $table = 'entity_'.$nameField;

        $sql = "INSERT INTO $table (`externalId`) VALUES ('$id')";

        try {
            $this->pdo->exec($sql);
            $result = true;
        }
        catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    public function newInternalId($obj, $entity)
    {
        $sql = "INSERT INTO $entity (";
        $fields = array();

        foreach ($obj as $key => $field) {
            if ($field instanceof PrimaryField) {
                $sql .= $key.", ";
            } else if ($field instanceof ReferenceField) {
                $sql .= $field->getName().", ";
            } else {
                $sql .= $field->getName().", ";
                $fields[$field->getName()] = $field->getValue();
            }

        }

        $sql = substr($sql, 0, -2);
        $sql .= ") VALUES (";

        foreach ($obj as $field) {
            if ($field instanceof PrimaryField) {
                $sql .= "null, ";
            }
            else if ($field instanceof ReferenceFieldMultiple) {
                $sql .= $field->getValue().", ";
            } else {
                $sql .= "'".$field->getValue()."', ";
            }
        }
        $sql = substr($sql, 0, -2);
        $sql .= ")";

        try {
            $this->pdo->exec($sql);
            $result = $this->findByInternalField($fields, $entity);
        }
        catch (Exception $e) {
            $result['id'] = filter_var($e->errorInfo[2], FILTER_SANITIZE_NUMBER_INT);
        }
        return $result;
    }

    public function findByInternalField($fields, $entity)
    {
        $sql = "SELECT * FROM $entity WHERE ";

        foreach ($fields as $key => $value) {
            $sql .= $key." = ".":".$key." AND ";
        }
        $sql = substr($sql, 0, -5);
        $sql .= " limit 1";

        $q = $this->pdo->prepare($sql);

        foreach ($fields as $key => $value) {
            $q->bindValue(':'.$key, $value);
        }

        try {
            $q->execute();
            $result = $q->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
        }
        catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

}
?>