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

    public function findByInternalId($id, $nameField)
    {
        $table = $nameField;

        $q = $this->pdo->prepare("SELECT * FROM $table WHERE `id` = :id limit 1");
        $q->bindValue(':id', $id);
        $q->execute();

        try {
            $q->execute();
            $result = $q->fetch(PDO::FETCH_ASSOC)['id'];
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
             `externalId` VARCHAR ( 50 ) NOT NULL,
             `internalId` VARCHAR ( 50 ) NOT NULL);";
            $this->pdo->exec($sql);
            CLIMessage::show("Created $name Table", "success");
            return $name;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function createEntityRef($name, $entity)
    {
        $table = $entity.'_'.$name;
        try {
            $sql ="CREATE table $table(
             $entity VARCHAR ( 50 ) NOT NULL,
             $name VARCHAR ( 50 ) NOT NULL);";
            $this->pdo->exec($sql);
            CLIMessage::show("Created $name Table", "success");
            return $name;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function newExternalId($id, $nameField, $internalId)
    {
        $table = 'entity_'.$nameField;

        $sql = "INSERT INTO $table (`externalId`, `internalId`) VALUES ('$id', '$internalId')";

        try {
            $this->pdo->exec($sql);
            $result = true;
        }
        catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    public function setReference($id, $ids, $name, $referName)
    {
        $table = $name."_".$referName;
        $result = null;

        foreach ($ids as $i) {
            if(!$this->checkExistRefer($id, $i, $name, $referName)){
                $sql = "INSERT INTO $table (`$name`, `$referName`) VALUES ('$id', '$i')";

                try {
                    $this->pdo->exec($sql);
                    $result = true;
                }
                catch (Exception $e) {
                    $result = false;
                }
            }
        }

        return $result;
    }

    protected function checkExistRefer($id, $i, $name, $referName)
    {
        $table = $name."_".$referName;

        $q = $this->pdo->prepare("SELECT * FROM $table WHERE `$name` = :id AND `$referName` = :idRef limit 1");
        $q->bindValue(':id', $id);
        $q->bindValue(':idRef', $i);
        $q->execute();

        try {
            $q->execute();
            $result = $q->fetch(PDO::FETCH_ASSOC);
            $result = $result ? true : false;
        }
        catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    public function newInternalId($obj, $entity, $existId = null)
    {
        $sql = "INSERT INTO $entity (";
        $fields = array();

        foreach ($obj as $key => $field) {
            if ($field instanceof PrimaryField) {
                $sql .= $key . ", ";
            } else if ($field instanceof AggregateField) {
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
                if ($existId) {
                    $sql .= $existId.", ";
                } else {
                    $sql .= "null, ";
                }
            }
            else if ($field instanceof ReferenceFieldMultiple) {
                $sql .= $field->getValue().", ";
            } else {
                if ($field->getValue() == null) {
                    $sql .= "null, ";
                } else {
                    $sql .= "'".$field->getValue()."', ";
                }
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

    public function setEntity($id, $obj, $entity)
    {
        $sql = "UPDATE `$entity` SET ";
        $fields = array();

        foreach ($obj as $key => $field) {
            if ($field instanceof PrimaryField) {
                continue;
            } else if ($field instanceof ReferenceFieldMultiple) {
                continue;
            } else {
                $sql .= "`".$key."` = :".$key.", ";
                $fields[$field->getName()] = $field->getValue();
            }

        }

        $sql .= "`mock` = :mock, ";
        $sql = substr($sql, 0, -2);

        $sql .= ' WHERE `id` = :id';


        $q = $this->pdo->prepare($sql);
        $q->bindValue(':id', $id);
        $q->bindValue(':mock', 0);

        foreach ($obj as $key => $value) {
            if ($value instanceof PrimaryField) {
                continue;
            } else if ($value instanceof ReferenceFieldMultiple) {
                continue;
            } else {
                $q->bindValue(':'.$key, $value->getValue());
            }
        }

        try {
            $q->execute();
            $result = $this->findByInternalField($fields, $entity);
        }
        catch (Exception $e) {
            $result['id'] = filter_var($e->errorInfo[2], FILTER_SANITIZE_NUMBER_INT);
        }

        return $result;
    }

    public function isMock($id, $nameField)
    {
        $table = $nameField;

        $q = $this->pdo->prepare("SELECT * FROM $table WHERE `id` = :id AND `mock` = :mock limit 1");
        $q->bindValue(':mock', 1);
        $q->bindValue(':id', $id);
        $q->execute();

        try {
            $q->execute();
            $result = $q->fetch(PDO::FETCH_ASSOC)['id'];
            $result = $result ? true : false;
        }
        catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    public function emptyInternalId($obj, $entity)
    {
        $sql = "INSERT INTO $entity (";
        $fields = array();

        foreach ($obj as $key => $field) {
            if ($field instanceof PrimaryField) {
                $sql .= $key.", ";
            } else if ($field instanceof ReferenceField) {
                continue;
            } else if ($field instanceof ReferenceFieldMultiple) {
                continue;
            } else {
                $sql .= $field->getName().", ";
                $fields[$field->getName()] = $field->getValue();
            }

        }

        $sql .= "mock , ";
        $sql = substr($sql, 0, -2);
        $sql .= ") VALUES (";

        foreach ($obj as $field) {
            if ($field instanceof PrimaryField) {
                    $sql .= "null, ";
            }
            else if ($field instanceof ReferenceFieldMultiple) {
                continue;
            } else if ($field instanceof ReferenceField) {
                    continue;
            } else {
                $sql .= "null, ";
            }
        }
        $sql .= "1 , ";
        $sql = substr($sql, 0, -2);
        $sql .= ")";


        try {
            $this->pdo->exec($sql);
            $result = $this->pdo->lastInsertId('id');
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