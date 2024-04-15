<?php
include_once "include/include_header.php";
/*
$dump = databaseDump();

$message = "\nHi,\n\nAttached is the current automated database backup for Cpefs.\n\n";
$filename = "database_backup.sql";
$content = chunk_split(base64_encode($dump));
    $uid = md5(uniqid(time()));
    $header = "From: CPEFS <no-reply@cpefs.com.au>\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use diff. tyoes here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    //sendEmail(0,"jenny.hodgson1@gmail.com","Cpefs Database Backup",$header,"","backup_cron.php","backup","IT");
*/
