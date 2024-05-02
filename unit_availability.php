<?php
include "include/base_includes.php";
include "include/authenticator.php";
include("include/view/layout.php");
$today = getdate();
$units = GetUnits();

// Use the URL parameters for the year and month if available, otherwise use the current year and month
$currentYear = date('Y');
$currentMonth = date('n');

$year = isset($_GET['year']) ? intval($_GET['year']) : $currentYear;
$month = isset($_GET['month']) ? intval($_GET['month']) : $currentMonth;
$monthName = date('F',strtotime($year."-".$month."-01"));
include "include/functions_calendar.php";

require "include/view/unit_availability_view.php";