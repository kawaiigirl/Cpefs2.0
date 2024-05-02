<?php
Header("Content-type: application/octet-stream");
Header("Content-Disposition: attachment; filename=database_backup.sql");
session_start();
include_once "../include/common.php";
include_once "redirect_to_adminlogin.php";
echo databaseDump();
