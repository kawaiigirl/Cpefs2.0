<?php
const incPath = '../inc/';
session_start();
include_once "../inc/common.php";
include_once "redirect_to_adminlogin.php";
global $dbc;
$path = "";
$filename = "bookings.csv";
$fp = fopen($path.$filename, "wb");
if (!is_resource($fp))
    die("Cannot open $filename");
	
$str = '"Member Name","Unit","Date In","Date Out"';
$str .= "\015\012";

$res = $dbc->getResult($_SESSION["QUERY"],__LINE__,__FILE__);
while($row = mysqli_fetch_array($res))
{
	$datein = date("d M Y",strtotime($row["check_in_date"]));
	$dateout = date("d M Y",strtotime("+$row[nights] day",strtotime($row["check_in_date"])));
	
	$str .= '"'.$row["member_name"].'","'.$row["unit_name"].'","'.$datein.'","'.$dateout.'"';
	$str .= "\015\012";
}
fwrite($fp, $str);
fclose($fp);
/* download code starts now */
$fp=fopen($path.$filename, "rb") ;
$contents = fread($fp, filesize($path.$filename));
fclose ($fp);
header('Pragma: public');
header('Cache-Control: max-age=0');
header ("Content-Type:application/x-download\n");
header ("Content-Disposition:attachment;filename=$filename\n\n");
print $contents;