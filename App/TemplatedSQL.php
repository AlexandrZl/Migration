<?php
class TemplatedSQL
{
    private $pdo;

    public function __construct ()
    {
        global $PDO;
        $this->pdo = $PDO;
    }

    public function findByExternalId($id, $type)
    {

        $q = $this->pdo->prepare("SELECT * FROM mappedDB WHERE externalId = :id and type = :type limit 1");
        $q->bindValue(':id', $id);
        $q->bindValue(':type', $type);
        $q->execute();

        $result = $q->fetch(PDO::FETCH_ASSOC);

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

    public function newInternalId($obj, $entity, $id = null)
    {
        $primField = array();
        $id = null;

        $sql = "INSERT INTO $entity (";

        foreach ($obj as $field) {
            if ($field instanceof PrimaryField) {
                if ($id && $field->getValue() == $id) {
                    $primField[$field->getName()] = $id;
                } else {
                    $primField[$field->getName()] = $field->getValue();
                }
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
            $result['existId'] = filter_var($e->errorInfo[2], FILTER_SANITIZE_NUMBER_INT);
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

    public function createMap()
    {
        $table = "mappedDB";
        try {
            $sql ="CREATE table $table(
             externalId VARCHAR( 50 ) PRIMARY KEY NOT NULL,
             internalId INT( 11 ) NOT NULL,
             type VARCHAR( 50 ) NOT NULL);" ;
            $this->pdo->exec($sql);
            CLIMessage::show("Created $table Table", "success");
        } catch(PDOException $e) {
        }
    }
}
?>