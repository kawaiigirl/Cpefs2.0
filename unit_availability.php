<?php
include "include/base_includes.php";
include "include/authenticator.php";
include("include/view/layout.php");
$today = getdate();

$prevmnth=$nextmnth =$monnn=$year=$mon= "";
if(!isset($_GET['year']) || $_GET['year']=="")
{

    $mon = $today['mon']; //month
    $year = $today['year']; //this year
    $day = $today['mday']; //this day
    $monnn = $today['month']; //month as string
}
else
{
    $mon = $_GET['month'];
    $year = $_GET['year'];
    $day = $today['mday']; //this day
    $monnn = getMonth($_GET['month']);
}

$noOfDays=date("t",mktime(0,0,0,$mon,1,$year));

$current_dateStart=date("Y-m-d",mktime(0,0,0,$mon,1,$year));
$current_dateEnd=date("Y-m-d",mktime(0,0,0,$mon,31,$year));

$prevyr=$year;
$nextyr=$year;

if($mon==1)
{
    $prevmnth=12;
    $prevyr=$year-1;
    $nextmnth=$mon+1;
}
elseif($mon<12)
{
    $prevmnth=$mon-1;
    $nextmnth=$mon+1;
}
else
{
    $prevmnth=$mon-1;
    $nextmnth=1;
    $nextyr=$year+1;
}

$day1 = $day-1;

$my_time= mktime(0,0,0,$mon,1,$year);
$start_mon = date('d', $my_time); //Month starting date
$start_day = date('D', $my_time); //Month starting Day
$start_daynum = date('w', $my_time);
$daysIM =$noOfDays;


$units = GetUnits();
function getLink($dd, $mm, $yy, $unitID): string
{

        global $dbc;
        $check_date = date('Y-m-d', mktime(0, 0, 0, $mm, $dd, $yy));

        $sql = "Select * from cpefs_booking Where  TO_DAYS(?) >= TO_DAYS(check_in_date)
		  And TO_DAYS(?)<= TO_DAYS(check_in_date)+nights-1 And unit_id=? And approve<>3";
        $res = $dbc->getResult($sql, __LINE__, __FILE__, array("ssi", &$check_date, &$check_date, &$unitID));
        $row = $res->fetch_array(MYSQLI_ASSOC);
        if ($res->num_rows > 0)
        {
            if ($row['approve'] == 1 || $row['approve'] == 0)
                return '<td bgcolor="#33CCFF"><div align="center">Pending</div></td>';
            elseif ($row['approve'] == 2)
                return '<td bgcolor="#FF0000"><div align="center">Booked</div></td>';
        }
        else
        {
            $sqlP = "Select * from cpefs_peak_periods Where TO_DAYS(?) >= TO_DAYS(peak_period_start_date)
			And TO_DAYS(?)<= TO_DAYS(peak_period_end_date)";
            $res = $dbc->getResult($sqlP, __LINE__, __FILE__, array("ss", &$check_date, &$check_date));
            if ($res->num_rows)
                return '<td bgcolor="#ffffaa"><div align="center">Peak</div></td>';
            return '<td height="18">&nbsp;</td>';
        }

    return'<td height="18">&nbsp;</td>';
}


// Use the URL parameters for the year and month if available, otherwise use the current year and month
$currentYear = date('Y');
$currentMonth = date('n');

$year = isset($_GET['year']) ? intval($_GET['year']) : $currentYear;
$month = isset($_GET['month']) ? intval($_GET['month']) : $currentMonth;
$monthName = date('F',strtotime($year."-".$month."-01"));
include "include/functions_calendar.php";

require "include/view/unit_availability_view.php";