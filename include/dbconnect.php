<?php
include_once "config.php";
include "classes/Class_DBC.php";

$db_port = 3306;

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME, $db_port);
$dbc = new DBC($mysqli,ERRORLOG_TABLE,ERROR_CONTACT);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
mysqli_query($mysqli,"SET time_zone = '+10:00'");

$dbc->openPDO();
$dbc->getResultPDO("SET time_zone = '+10:00'",__LINE__,__FILE__);