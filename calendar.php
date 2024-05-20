<?php
include "include/authenticator.php";
function isWeekend($date): bool
{
    return (date('N', strtotime($date)) >= 6);
}
function getDayClass($dd, $mm, $yy): string
{
    global $dbc;
    $check_date = date('Y-m-d', mktime(0, 0, 0, $mm, $dd, $yy));
    $sql = "Select * from cpefs_booking Where  TO_DAYS(?) >= TO_DAYS(check_in_date) 
		  And TO_DAYS(?)<= TO_DAYS(check_in_date)+nights-1 And unit_id=? And approve<>3";
    $res = $dbc->getResult($sql, __LINE__, __FILE__, array("ssi", &$check_date, &$check_date, &$_GET['id']));
    $row = mysqli_fetch_array($res);
    if (mysqli_num_rows($res) > 0)
    {
        if ($row['approve'] == 1 || $row['approve'] == 0)
            return "Green";
        elseif ($row['approve'] == 2)
            return "Blue";
    }
    return "";
}

$today = getdate();
if(!isset($_GET['yr']) || $_GET['yr']=="")
{
	
	$month = $today['mon']; //month
	$year = $today['year']; //this year
	$monthName = $today['month']; //month as string
}
else
{
	$month = $_GET['month'];
	$year = $_GET['yr'];
	$monthName = getMonth($_GET['month']);
}

$noOfDays=date("t",mktime(0,0,0,$month,1,$year));

$current_dateStart=date("Y-m-d",mktime(0,0,0,$month,1,$year));
$current_dateEnd=date("Y-m-d",mktime(0,0,0,$month,31,$year));

$previousYear=$year;
$nextYear=$year;
if($month==1)
{
	$previousMonth=12;
	$previousYear=$year-1;
	$nextMonth=$month+1;
}
elseif($month<12)
{
	$previousMonth=$month-1;
	$nextMonth=$month+1;
}
else
{
	$previousMonth=$month-1;
	$nextMonth=1;
	$nextYear=$year+1;
}

$my_time= mktime(0,0,0,$month,1,$year);
$start_mon = date('d', $my_time); //Month starting date
$start_day = date('D', $my_time); //Month starting Day
$startDayNumber = (int)date('w', $my_time);
$startDayNumber = ((int)date('w', $my_time) === 0) ? 6 : $startDayNumber - 1;
$daysInMonth =$noOfDays;
?>
<div class="unit-calendar">
    <div class="calendar-header">
        <div class="calendar-month"><strong><?php echo $monthName. " ".$year; ?></strong></div>
        <button class="prev-month" style="margin-top: -19px;" onclick="window.location.href='unit_details.php?id=<?=$_GET['id']?>&yr=<?=$previousYear?>&month=<?=$previousMonth?>#cal'">&#8249;</button>
	    <button class="next-month" style="margin-top: -19px;" onclick="window.location.href='unit_details.php?id=<?=$_GET['id']?>&yr=<?=$nextYear?>&month=<?=$nextMonth?>#cal'">&#8250;</button>
    </div>
  <div class="calendar-days">
      <div class='calendar-day no-pointer'>Mon</div>
      <div class='calendar-day no-pointer'>Tue</div>
      <div class='calendar-day no-pointer'>Wed</div>
      <div class='calendar-day no-pointer'>Thu</div>
      <div class='calendar-day no-pointer'>Fri</div>
      <div class='calendar-day weekend no-pointer'>Sat</div>
      <div class='calendar-day weekend no-pointer'>Sun</div>
<?php
	$dd = 0;
	$day = 1;
	while($dd < $startDayNumber)
	{

        $addClass = "";
        if($dd == 5)
        {
            $addClass = " weekend";
        }
		echo  "<div class='calendar-day $addClass no-pointer'></div>";
		$dd++;
	}
	while($day <= $daysInMonth)
	{
        $addClass = "";

        if(isWeekend($year."-".$month."-".$day))
        {
            $addClass = " weekend";
        }
        echo "<div class='calendar-day ". getDayClass($day,$month,$year)."$addClass no-pointer'>$day</div>";
        $day++;
        $dd++;
	}
	?>
</div>
</div>
