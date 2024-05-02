<?php
class DBC
{
	private mysqli $mysqli;
	private string $errorlogTable;
	private string $errorContact;
    private PDO    $pdo;

    public function __construct(mysqli $mysqliConn, string $errorlogTable, string $errorContact)
	{
		$this->mysqli = $mysqliConn;
		$this->errorlogTable = $errorlogTable;
		$this->errorContact = $errorContact;
	}
	//cpefs branch

    public function openPDO(): void
    {
        try {
            $dsn      = "mysql:dbname=".DB_NAME."; host=".DB_HOST;
            $user     = DB_USER;
            $password = DB_PWD;

            $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                          PDO::ATTR_DEFAULT_FETCH_MODE                      => PDO::FETCH_ASSOC,
        );
            $this->pdo = new PDO($dsn, $user, $password, $options);
            $this->pdo->query("SET time_zone = '+10:00'");

        } catch (PDOException $e) {
            if(LOCALHOST)
             echo 'Connection failed: ' . $e->getMessage();
        }
    }
//deprecated
//creates a reference array for mysqli binding parameters
    /** @noinspection PhpArrayAccessCanBeReplacedWithForeachValueInspection */
    public static function valRef(& $arr): array
    {
        $refs = array();

        foreach ($arr as $key => $value)
        {
            $refs[$key] =&$arr[$key]; //ignore php storms suggestion
        }
        return $refs;
    }
    public function getInsertID(): int|string
    {
        return $this->mysqli->insert_id;
    }
    private function mysqliLogError($mysqli,$qry,$msg,$line,$file): void
    {
        echo "<p style='color:red;'>There has been a database error, please contact $this->errorContact </p>";
        $this->LogError("SQL: $qry Error Number:  $mysqli->errno Msg: $msg File: $file Line: $line ");
        if(LOCALHOST)
        {
            // the query failed and debugging is enabled
            echo "<p style='color:red;border:solid black 1px;background:white;'>$msg";
            echo "<br/><br/>Error on line number: <span style='color:black;'><strong>". $line ."</strong></span> of file:  <span style='color:black;'><strong>". $file ."</strong></span><br/>";
            echo "In query: $qry<br/><br/>Error number:";
            echo $mysqli->errno." Mysqli error:".$mysqli->error ."</p>";
            Die();
        }
    }
/////reports mysqli errors
    private function mysqliErrorCheck($res,$qry,$line,$file): void
    {
        if (!$res )
        {
            $this->mysqliLogError($this->mysqli,$qry,"",$line,$file);
            echo "<p style='color:red;border:solid black 1px;background:white;'>There has been a database error please contact $this->errorContact</p><br/>";
        }
    }
    public function getNumRows($qry,$line,$file,$parameters=null): int|string
    {
        $result=$this->preparedQuery($qry,$parameters,$line,$file);
        if(is_a($result, 'mysqli_result')) {
            return mysqli_num_rows($result);
        }
        else
        {
            return mysqli_num_rows($result->get_result());
        }
    }
    public function update($qry,$line,$file,$parameters):bool
    {
        $result =  $this->preparedQuery($qry,$parameters,$line,$file);
        if($result->errno == 0)
            return true;
        else
            return false;
    }
    public function insert($qry,$line,$file,$parameters) :bool
    {
        $result =  $this->preparedQuery($qry,$parameters,$line,$file);
        if($result->errno == 0)
            return true;
        else
            return false;
    }
    public function delete($qry,$line,$file,$parameters):bool
    {
        $result = $this->preparedQuery($qry,$parameters,$line,$file);
        if($result->errno == 0)
            return true;
        else
            return false;
    }
    private function LogError($errorMessage): void
    {
        $qry = "INSERT INTO $this->errorlogTable SET $this->errorlogTable.error_message = ?,$this->errorlogTable.date=now()";
        $parameters = array("s",& $errorMessage);
        $this->insert($qry,__LINE__,__FILE__,$parameters);
    }
    /*public function runMultipleQueries($sql,$line,$file):bool
    {
        $qry =  "Start Transaction;";
        $qry .= $sql;
        $qry .= "Commit;";
        $res = $this->mysqli->multi_query($qry);
        While($this->mysqli->more_results())
        {
            $this->mysqliErrorCheck($this->mysqli->next_result(),$qry,$line,$file);
        }
        return $res;
    }*/
    public function getLastInsertID(): string
    {
        return $this->pdo->lastInsertId();
    }
    public function getNumRowsPDO($qry,$line,$file,$parameters=null): int
    {
        $result=$this->preparedPDOQuery($qry, $line, $file, $parameters);
        return $result->rowCount();
    }
    public function doesThisExistPDO($qry,$line,$file,$parameters=null): bool
    {
        if($this->getNumRowsPDO($qry,$parameters,$line,$file)>0)
            return true;
        else
            return false;
    }
    public function updatePDO($qry,$line,$file,$parameters):bool
    {
        $result =  $this->preparedPDOQuery($qry, $line, $file, $parameters);
        if($result)
            return true;
        else
            return false;
    }
    public function insertPDO($qry,$line,$file,$parameters) :bool
    {
        $result =  $this->preparedPDOQuery($qry, $line, $file, $parameters);
        if($result)
            return true;
        else
            return false;
    }
    public function deletePDO($qry,$line,$file,$parameters):bool
    {
        $result = $this->preparedPDOQuery($qry, $line, $file, $parameters);
        if($result)
            return true;
        else
            return false;
    }
    public function getSingleDataPDO($qry,$line,$file,$parameters = null)
    {
        $res = $this->getResultPDO($qry,$line,$file,$parameters);
        $row = $res->fetch(PDO::FETCH_NUM);
        return $row[0];
    }
    public function getSingleRowPDO($qry, $line, $file, $parameters = null)
    {
        $res = $this->getResultPDO($qry,$line,$file,$parameters);
        return $res->fetch();
    }
    public function getResultPDO($qry,$line,$file,$parameters = null): bool|PDOStatement
    {
        return $this->preparedPDOQuery($qry, $line, $file, $parameters);
    }
    public function getArrayResult($qry, $line, $file, $parameters = null):array
    {
        $res = $this->preparedPDOQuery($qry, $line, $file, $parameters);
        if($res)
            return  $res->fetchAll(PDO::FETCH_ASSOC);
        else
            return array();
    }
    private function preparedPDOQuery($qry, $line, $file, $parameters): bool|PDOStatement
    {
        if ($parameters != null)
        {
            $stmt = $this->pdo->prepare($qry);

            $stmt->execute($parameters);
            if($stmt == false)
            {
                $this->logErrorPDO($qry,$line,$file,$parameters);
            }
            return $stmt;

        }
        return $this->queryPDO($qry,$line,$file);
    }
    private function queryPDO($qry,$line,$file): bool|PDOStatement
    {
        $res = $this->pdo->query($qry);
        if(!$res)
            $this->logErrorPDO($qry,$line,$file);
        return $res;
    }
    private function logErrorPDO($qry,$line,$file,$parameters = null): void
    {
        echo "<p style='color:red;border:solid black 1px;background:white;'>There has been a database error please contact $this->errorContact</p><br/>";
        $msg = implode(":",$this->pdo->errorInfo());

        $this->LogError("SQL: $qry Error message: $msg Parameters: ".implode(',',$parameters)." File: $file Line: $line ");
        if(LOCALHOST)
        {
            // the query failed and debugging is enabled
            echo "<p style='color:red;border:solid black 1px;background:white;'>$msg";
            echo "<br/><br/>Error on line number: <span style='color:black;'><strong>". $line ."</strong></span> of file:  <span style='color:black;'><strong>". $file ."</strong></span><br/>";
            echo "In query: $qry<br/><br/>Error number:";
            Die();
        }
    }
    ///Mysqli functions ::deprecated
    public function getSingleData($qry,$line,$file,$parameters = null)
    {
        $res = $this->getResult($qry,$line,$file,$parameters);
        $row = $res->fetch_array(MYSQLI_NUM);
        return $row[0];
    }
    public function getSingleRow($qry, $line, $file, $parameters = null)
    {
        $res = $this->getResult($qry,$line,$file,$parameters);
        return $res->fetch_array(MYSQLI_ASSOC);
    }
    public function getResult($qry,$line,$file,$parameters = null)// : bool|array php 8
    {
        if($parameters != null) {
            $stmt =$this->preparedQuery($qry, $parameters, $line, $file);
            if(is_a($stmt, 'mysqli_result'))
                return $stmt;
            else
                return $stmt->get_result();
        }
        else
            return $this->query($qry, $line, $file);
    }

    private function preparedQuery($qry,$parameters,$line,$file): mysqli_result|bool|mysqli_stmt
    {
        if ($parameters != null)
        {
            if (!($stmt =$this->mysqli->prepare($qry))) {
                $msg = "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
                $this->mysqliLogError($stmt, $qry, $msg, $line, $file);
                //  return false;
            }

            //bind parameters
            if (!call_user_func_array(array($stmt, 'bind_param'), $parameters)) {
                $msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "Parameter: " . '"' . implode('","', $parameters) . '"';
                $this->mysqliLogError($stmt, $qry, $msg, $line, $file);
                //   return false;
            }

            //execute
            if (!$stmt->execute()) {
                $msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $this->mysqliLogError($stmt, $qry, $msg, $line, $file);
                //  return false;
            }
            /*$res =  $stmt->get_result();
            if($res == false)
                return true;
            else
                return $res;*/
            return $stmt;
        }
        return $this->query($qry,$line,$file);
    }
/////executes mysqli query with error checking etc.
    private function query($qry,$line,$file): mysqli_result|bool
    {
        $res = $this->mysqli->query($qry);
        $this->mysqliErrorCheck($res,$qry,$line,$file);
        return $res;
    }



}