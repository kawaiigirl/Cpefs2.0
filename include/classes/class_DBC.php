<?php
class DBC
{

    private string $errorlogTable;
    private string $errorContact;
    private PDO    $pdo;

    public function __construct(string $errorlogTable, string $errorContact)
    {
        $this->errorlogTable = $errorlogTable;
        $this->errorContact = $errorContact;

        $dsn      = "mysql:dbname=".DB_NAME."; host=".DB_HOST;
        $user     = DB_USER;
        $password = DB_PWD;

        $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                          PDO::ATTR_DEFAULT_FETCH_MODE                      => PDO::FETCH_ASSOC,
        );
        $this->pdo = new PDO($dsn, $user, $password, $options);
        $this->pdo->query("SET time_zone = '+10:00'");
    }

    public function LogError($errorMessage)
    {
        $qry = "INSERT INTO $this->errorlogTable SET error_message = :msg,date=:now";
        $parameters = array('msg'=>$errorMessage,'now'=>Now());
        $this->insert($qry,__LINE__,__FILE__,$parameters);
    }
    public function getLastInsertID(): string
    {
        return $this->pdo->lastInsertId();
    }
    public function getNumRows($qry, $line, $file, $parameters=null): int
    {
        $result=$this->preparedQuery($qry, $line, $file, $parameters);
        if($result)
            return $result->rowCount();
        return 0;
    }
    public function doesThisExist($qry, $line, $file, $parameters=null): bool
    {
        if($this->getNumRows($qry, $line, $file,$parameters)>0)
            return true;
        else
            return false;
    }
    public function update($qry, $line, $file, $parameters):bool
    {
        $result =  $this->preparedQuery($qry, $line, $file, $parameters);
        if($result)
            return true;
        else
            return false;
    }
    public function updateWithRowCount($qry, $line, $file, $parameters):bool
    {
        $result =  $this->preparedQuery($qry, $line, $file, $parameters);
        if($result)
            return $result->rowCount();
        else
            return false;
    }
    public function insert($qry, $line, $file, $parameters) :bool
    {
        $result =  $this->preparedQuery($qry, $line, $file, $parameters);
        if($result)
            return true;
        else
            return false;
    }
    public function delete($qry, $line, $file, $parameters):bool
    {
        $result = $this->preparedQuery($qry, $line, $file, $parameters);
        if($result)
            return true;
        else
            return false;
    }
    public function getSingleData($qry, $line, $file, $parameters = null)
    {
        $res = $this->preparedQuery($qry, $line, $file, $parameters);
        if($res)
        {
            $row = $res->fetch(PDO::FETCH_NUM);
            if($row)
                return $row[0];
        }
        return false;
    }
    public function getSingleRow($qry, $line, $file, $parameters = null)
    {
        $res = $this->preparedQuery($qry, $line, $file, $parameters);
        if($res)
            return $res->fetch();
        else
            return false;
    }
    public function getResult($qry, $line, $file, $parameters = null): array
    {
        $res = $this->preparedQuery($qry, $line, $file, $parameters);
        if($res)
            return  $res->fetchAll(PDO::FETCH_ASSOC);
        else
            return array();
    }
    private function preparedQuery($qry, $line, $file, $parameters)
    {
        if ($parameters != null)
        {
            try
            {
                $stmt = $this->pdo->prepare($qry);
                $stmt->execute($parameters);
                return $stmt;
            }
            catch (Exception $e)
            {
                Functions::LogException($e);
                $this->reportError($qry, $line, $file, $parameters,$stmt);
                return false;
            }
        }
        return $this->query($qry, $line, $file);
    }
    private function query($qry, $line, $file)
    {
        $res = $this->pdo->query($qry);
        if(!$res)
            $this->reportError($qry, $line, $file);
        return $res;
    }
    private function reportError($qry, $line, $file, $parameters = null,PDOStatement $stmt = null)
    {

        $msg = implode(" : ",$stmt->errorInfo());
        $errorCode = $stmt->errorCode();

        $errorMessage = "SQL: $qry | Error code: $errorCode | Error message: $msg | Parameters: ".implode(',',$parameters)." | File: $file | Line: $line ";
        if(LOCALHOST)
        {
            echo "<p style='color:red;border:solid black 1px;background:white;'>$errorMessage<p>";
        }
        else
        {
            echo "<p style='color:red;border:solid black 1px;background:white;'>There has been a database error</p><br/>";

        }
        $this->LogError($errorMessage);
    }
    public static function get_enum_values( $table, $field )
    {
        global $dbc;
        $type = $dbc->getResult("SHOW COLUMNS FROM $table WHERE Field = '$field'",__LINE__,__FILE__)[0]["Type"];
        preg_match("/^enum\('(.*)'\)$/", $type, $matches);//"/^enum\(\'(.*)\'\)$/"
        return explode("','", $matches[1]);
    }



}