<?php

function isFutureDate($date): bool
{
    return date("Y-m-d",strtotime($date)) >= date("Y-m-d");
}
function getAdminPagingVariables($qry, $page, $qryParams = null, $qryParamTypes= null): array
{
    global $dbc;
    $returnArray = array();

    if($qryParams != null && count($qryParams)>0 && $qryParamTypes !=null)
    {
        array_unshift($qryParams, $qryParamTypes);
        $returnArray['paramsRefs']= DBC::valRef($qryParams);
         $returnArray['rows']=$dbc->getNumRows($qry,__LINE__,__FILE__,$returnArray['paramsRefs']);	/// setting pagination variables
    }
    else
    {
        $returnArray['paramsRefs'] = null;
        $returnArray['rows']=$dbc->getNumRowsPDO($qry,__LINE__,__FILE__,$qryParams);	/// setting pagination variables
    }

    $page_record=$dbc->getSingleRow("select records_display_admin from cpefs_settings",__LINE__,__FILE__);
    $returnArray['toshow']=$page_record["records_display_admin"];
    $returnArray['totalPages']=ceil($returnArray['rows']/$returnArray['toshow']);
    if($page == "highest")
    {
        $returnArray['page'] = $returnArray['totalPages'];
    }
    else
        $returnArray['page'] = $page;
    $returnArray['start']=($returnArray['page']-1)*$returnArray['toshow'];

    return $returnArray;
}
function datadump ($table): string
{
    global $dbc;
    $result = "# Dump of $table \n";
    $result .= "# Dump DATE : " . date("d-M-Y") ."\n\n";

    $qry = "select * from ".$table;
    $res = $dbc->getResult( $qry,__LINE__,__FILE__);
    $num_fields = @mysqli_num_fields($res);
    $numrow = $dbc->getNumRows($qry,__LINE__,__FILE__);


    for ($i =0; $i<$numrow; $i++) {
        $row = mysqli_fetch_array($res);
        $result .= "INSERT INTO ".$table." VALUES(";
        for($j=0; $j<$num_fields; $j++) {
            $row[$j] = addslashes($row[$j]);
            //$row[$j] = ereg_replace("\n","\\n",$row[$j]);
            if (isset($row[$j])) $result .= "\"$row[$j]\"" ; else $result .= "\"\"";
            if ($j<($num_fields-1)) $result .= ",";
        }
        $result .= ");\n";
    }
    return $result . "\n\n\n";
}
function databaseDump(): string
{
    $dump = datadump("cpefs_admin");
    $dump .= datadump("cpefs_booking");
    $dump .= datadump("cpefs_deleted_booking");
    $dump .= datadump("cpefs_emails");
    $dump .= datadump("cpefs_members");
    $dump .= datadump("cpefs_payments");
    $dump .= datadump("cpefs_peak_periods");
    $dump .= datadump("cpefs_settings");
return $dump;
}

function set_focus($obj): void
{
    if($obj !='')
    {
        echo "<script type='text/javascript'>document.getElementById('" . $obj . "').focus()</script>";
    }
}

function show_text($txt): string
{
	return nl2br( stripcslashes($txt));
}
function IsGetSetAndNotEmpty(string $variable): bool
{
    return isset($_GET[$variable]) && $_GET[$variable] != '' && $_GET[$variable] !='null';
}
function IsGetSetAndEquals(string $getVariable, string $equalTo): bool
{
    return isset($_GET[$getVariable]) && $_GET[$getVariable]==$equalTo;
}
function IsPostSetAndEquals(string $postVariable, string $equalTo): bool
{
    return isset($_POST[$postVariable]) && $_POST[$postVariable]==$equalTo;
}
function IsPostSetAndNotEmpty(string $variable): bool
{
    return isset($_POST[$variable]) && $_POST[$variable] != '';
}
function setFromPostOrGet($variable)
{
    if(isset($_POST[$variable]) && $_POST[$variable]!='')
    {
        return $_POST[$variable];
    }
    elseif(isset($_GET[$variable]) &&$_GET[$variable] != '')
    {
        return $_GET[$variable];
    }
    else
        return "";
}
function SetFromPost($variableName) : string
{
    if(isset($_POST[$variableName]) && $_POST[$variableName]!='')
    {
        return $_POST[$variableName];
    }
    return '';
}
function SetFromGet($variableName) : string
{
    if(isset($_GET[$variableName]) && $_GET[$variableName]!='')
    {
        return $_GET[$variableName];
    }
    return '';
}
function DisplayPost(string $variableName): void
{
    echo  SetFromPost($variableName);
}
function DisplayPostOrGet(string $variableName): void
{
    echo SetFromPostOrGet($variableName);
}
function get_admin_receive_email_id()
{
    global $dbc;
	$qry="select admin_receive_email from cpefs_settings ";
	return $dbc->getSingleDataPDO($qry,__LINE__,__FILE__);
}
function get_admin_send_email_id()
{
    global $dbc;
	$qry="select admin_send_email from cpefs_settings ";
	return $dbc->getSingleDataPDO($qry,__LINE__,__FILE__);
}
function getMonth($val): string
{
	if($val==1)
		return "January";
	elseif($val==2)
		return "February";
	elseif($val==3)
		return "March";
	elseif($val==4)
		return "April";
	elseif($val==5)
		return "May";
	elseif($val==6)
		return "June";
	elseif($val==7)
		return "July";
	elseif($val==8)
		return "August";
	elseif($val==9)
		return "September";
	elseif($val==10)
		return "October";
	elseif($val==11)
		return "November";
	elseif($val==12)
		return "December";
	return "Invalid Month";
}

function getFirstName($name): string
{
	return substr($name,0,strpos($name," "));
}

function getEndDate($cdate,$duration): string
{
	$d=date("d",strtotime($cdate));
	$m=date("m",strtotime($cdate));
	$y=date("Y",strtotime($cdate));
	
	return date("d M Y",mktime(0,0,0,$m,(int)$d+(int)$duration,$y));
}