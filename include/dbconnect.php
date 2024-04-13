<?php
session_start();
include_once "config.php";
include "classes/Class_DBC.php";

$dbc = new DBC(ERRORLOG_TABLE,ERROR_CONTACT);
