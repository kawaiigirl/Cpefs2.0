<?php

//old functions todo update to new with line and file
function getSingleData($qry)
{
//	echo $qry . "<br/>";

    //$res=mysql_query($qry) or die(mysql_error());
    //$row=mysql_fetch_array($res);
    //return $row;
    return getSingleRowQuery($qry,__LINE__,__FILE__);
}
function getNumRows($qry)
{
//	echo $qry;
//	exit;
    return getNumRowsMysqli($qry,__LINE__,__FILE__);
//	$res=mysql_query($qry) or die($qry . mysql_error());
    //$num=mysql_num_rows($res);
    //return $num;
}
function update($qry) //todo create update function with paramaters to sanitize
{
    //mysql_query($qry) or die(mysql_error());
    query($qry,__LINE__,__FILE__);
}
function insert($qry)
{
    //mysql_query($qry) or die(mysql_error());
    query($qry,__LINE__,__FILE__);
}


/////executes mysqli query with error checking etc.
function query($qry,$line,$file)
{

	$res = DBC::$mysqli->query($qry);
	mysqliErrorCheck($res,$qry,$line,$file);
	return $res;
}

function LogError($errorMessage)
{
	$qry = "INSERT INTO cpefs_error_log SET error_message = ?,date=now()";
	$parameters = array("s",& $errorMessage);
	insertQuery($qry,$parameters,__LINE__,__FILE__);
}

function runMultipleQueries($sql,$line,$file)
{
//Does this need START TRANSACTION; ???
	$qry =  "Start Transaction;";
	$qry .= $sql;
	$qry .= "Commit;";
	$res = DBC::$mysqli->multi_query($qry);
	
		While(DBC::$mysqli->more_results())
		{
			
			mysqliErrorCheck(DBC::$mysqli->next_result(),$qry,$line,$file);
			
			
		}
	return $res;
	//COMMIT;??
}
function returnMultipleQueries($qry,$line,$file)
{
//Does this need START TRANSACTION; ???

	$res = DBC::$mysqli->multi_query($qry);

	mysqliErrorCheck($res,$qry,$line,$file);
	
	return $res;
	//COMMIT;??
}
function get_result( $Statement )
{
    $RESULT = array();
    $Statement->store_result();
    for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
        $Metadata = $Statement->result_metadata();
        $PARAMS = array();
        while ( $Field = $Metadata->fetch_field() ) {
            $PARAMS[] = &$RESULT[ $i ][ $Field->name ];
        }
        call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
        $Statement->fetch();
    }
    return $RESULT;
}
///$parameters = array("s",& $altBody);
function insertQuery($qry,$parameters,$line,$file)
{
	if (!($stmt = DBC::$mysqli->prepare($qry))) {
		$msg = "Prepare failed: (" . DBC::$mysqli->errno . ") " . DBC::$mysqli->error;
		mysqliLogError($stmt,$qry,$msg,$line,$file);
	}
	//bind parameters
	if (!call_user_func_array(array($stmt, 'bind_param'), $parameters))
	{
		$msg = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "Parameter: ". '"'. implode('","',$parameters).'"';
		mysqliLogError($stmt,$qry,$msg,$line,$file);
	}
	//execute
	if (!$stmt->execute()) {
		$msg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		mysqliLogError($stmt,$qry,$msg,$line,$file);
	}
	
	
	//return $stmt->get_result();
	return get_result($stmt);
}
function getSingleDataQuery($qry,$line,$file)
{
	$res = query($qry,$line,$file);
	$row = $res->fetch_array(MYSQLI_NUM);
	return $row[0];
}
function getSingleRowQuery($qry,$line,$file)
{
	$res = query($qry,$line,$file);
	return $res->fetch_array(MYSQLI_ASSOC);
}
function mysqliLogError($mysqli,$qry,$msg,$line,$file)
{
	//$mysqlerror =  DBC::$mysqli->error;
	$mysqlerror =  $mysqli->error;
	echo "first error";
	echo "<p style='color:red;border:solid black 1px;background:white;'>$msg";
		echo "<br/><br/>Error on line number: <span style='color:black;'><strong>". $line ."</strong></span> of file:  <span style='color:black;'><strong>". $file ."</strong></span><br/>";
		echo "In query: $qry<br/><br/>Error number:";
		echo $mysqli->errno." Mysqli error:".$mysqlerror ."</p>";
	if(isset($_SESSION['debug']))
	{
		// the query failed and debugging is enabled
		echo "<p style='color:red;border:solid black 1px;background:white;'>$msg";
		echo "<br/><br/>Error on line number: <span style='color:black;'><strong>". $line ."</strong></span> of file:  <span style='color:black;'><strong>". $file ."</strong></span><br/>";
		echo "In query: $qry<br/><br/>Error number:";
		//echo $mysqli->errno." Mysqli error:".$mysqli->error ."</p>";
		Die();
	}
	else
	{
		LogError("SQL: $mysqlerror Error Number:  DBC::$mysqli->errno Msg: $msg File: $file Line: $line ");
	}
}
/////reports mysqli errors
function mysqliErrorCheck($res,$qry,$line,$file)
{
	$mysqlerror = DBC::$mysqli->error;
	if (!$res ) 
	{
		//if(isset($_SESSION['debug']))
		//{
			// the query failed and debugging is enabled
			echo "<p style='color:red;border:solid black 1px;background:white;'>Error on line number: <span style='color:black;'><strong>". $line ."</strong></span> of file:  <span style='color:black;'><strong>". $file ."</strong></span><br/>";
			echo "In query: $qry</p>";
			echo DBC::$mysqli->error;
			LogError("SQL: $mysqlerror File: $file Line: $line ");
		/*}
		else
		{
			LogError("SQL: $mysqlerror File: $file Line: $line ");
		}*/
		//Die();
	}

}

function getNumRowsMysqli($qry,$line,$file)
{
	$res=query($qry,$line,$file);
	return mysqli_num_rows($res);
}

/////////returns the whole table as an associate array
function getWholeTableArray($table_name,$line,$file)
{
	$qry="Select * FROM ".$table_name;
	//echo "<br>".$qry."<br>";
	$res = query($qry,$line,$file);
	
	if($res->num_rows != 0)
	{
		$rows = $res->fetch_all(MYSQLI_ASSOC);
	}
	else
	{
		$rows =  array();
	}
	return $rows;
}
/////////returns the whole table as an associate array
function getWholeTableArrayQuery($qry,$line,$file)
{
	$res = query($qry,$line,$file);
	
	if($res->num_rows != 0)
	{
		$rows = $res->fetch_all(MYSQLI_ASSOC);
	}
	else
	{
		$rows = array();
	}
	
	return $rows;
}