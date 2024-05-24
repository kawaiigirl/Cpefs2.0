<?php

function isFutureDate($date): bool
{
    return date("Y-m-d",strtotime($date)) >= date("Y-m-d");
}
function GetCheckOutDate($check_in_date,$nights): string
{
    return date('Y/m/d', strtotime($check_in_date. ' + '.$nights .'day'));
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
function GetMemberFormHtml($errors): void
{
?>
    <div class="leftColumn leftInner">
        <div class="row">
            <label for="member_email">Email</label><input type="text" name="member_email" id="member_email" value="<?=SetFromPost('member_email') ?>">
            <?= SetFromArray($errors,'email') ?></div>
        <div class="row"><label for="member_firstname">First Name</label>
            <input type="text" name="member_firstname" id="member_firstname" value="<?=SetFromPost('member_firstname') ?>"><?= SetFromArray($errors,'firstname') ?>
        </div>
        <div class="row"><label for="member_lastname">Last Name</label>
            <input type="text" name="member_lastname" id="member_lastname" value="<?=SetFromPost('member_lastname') ?>"><?= SetFromArray($errors,'lastname') ?>
        </div>
        <div class="row">
            <label for='member_address'>Address</label><input type="text" name="member_address" id="member_address" value="<?=SetFromPost('member_address') ?>"><?=SetFromArray($errors,'address') ?></div>
    </div>
    <div class="rightColumn rightInner">
    <div class="row">
        <div class="row"><label for="member_suburb">Suburb</label>
            <input type="text" name="member_suburb" id="member_suburb" value="<?=SetFromPost('member_suburb') ?>"><?= SetFromArray($errors,'suburb') ?></div>
        <label for="member_postcode">Postcode</label><input type="text" name="member_postcode" id="member_postcode" value="<?=SetFromPost('member_postcode') ?>"><?=  SetFromArray($errors,'postcode') ?></div>
    <div class="row"><label for="member_telephone">Telephone</label>
        <input type="text" name="member_telephone" id="member_telephone" value="<?= SetFromPost('member_telephone') ?>"><?= SetFromArray($errors,'phone') ?></div>
    </div>
<?php
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
function SetFromArray($array,$variableName)
{
    if(isset($array[$variableName]) && $array[$variableName]!='')
    {
        return $array[$variableName];
    }
    return "";
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