<?php
include "include/authenticator.php";

function getDay($dd, $mm, $yy): string
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
            return "<b><td class='Green'>$dd</td>";
        elseif ($row['approve'] == 2)
            return "<b><td class='Blue'>$dd</td>";
    }
    else
        return "<td>$dd</td>";
    return "<td>$dd</td>";
}

$bgcolor="#FFFFFF";
$tablecolor = "#FFFFFF";
$fontcolor = "#000000";
$today = getdate();
if(!isset($_GET['yr']) || $_GET['yr']=="")
{
	
	$mon = $today['mon']; //month
	$year = $today['year']; //this year
	$day = $today['mday']; //this day
	$monnn = $today['month']; //month as string
}
else
{
	$mon = $_GET['mnth']; 
	$year = $_GET['yr']; 
	$day = $today['mday']; //this day
	$monnn = getMonth($_GET['mnth']);
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
?>
<table class="unit-calendar" border="0" align="center" cellpadding="0" cellspacing="5" >
  <tr> 
    <td height="20" colspan="7"> <div align="center"><a href="#" name="cal"></a>
	<a href="unit_details.php?id=<?php echo $_GET['id']?>&yr=<?php echo ($prevyr)?>&mnth=<?php echo ($prevmnth)?>#cal" class="calendarlink">&lt; previous month &gt;</a>
	&nbsp; 
	<strong><?php echo $monnn. " ".$year; ?></strong>&nbsp; 
	<a href="unit_details.php?id=<?php echo $_GET['id']?>&yr=<?php echo ($nextyr)?>&mnth=<?php echo ($nextmnth)?>#cal" class="calendarlink">&lt; next month &gt;</a>
	</div>
   </td>
  </tr>
  <tr> 
    <td height="20"><div><strong>S</strong></div></td>
    <td height="20"><div><strong>M</strong></div></td>
    <td height="20"><div><strong>T</strong></div></td>
    <td height="20"><div><strong>W</strong></div></td>
    <td height="20"><div><strong>T</strong></div></td>
    <td height="20"><div><strong>F</strong></div></td>
    <td height="20"><div><strong>S</strong></div></td>
  </tr>
  <?php
	$dd = 0;
	$daye = 1;
	echo "<tr style='background-color: $tablecolor'>";
	while($dd < $start_daynum)
	{
		echo  "<td style='background-color: $bgcolor'></td>";
		$dd = $dd+1;
	}
	
	while($dd < 7)
	{
		$matter=getDay($daye,$mon,$year);
		echo  $matter;
		$daye++;
		$dd++;
	}
	echo "</tr>";
	
	$noOfRows=1;
	while($daye <= $daysIM)
	{
		echo "<tr style='background-color: $tablecolor'>";
		$noOfRows +=1;
		$dd = 0;
		while($dd<7)
		{
			if($daye <= $daysIM)
			{
				$matter=getDay($daye,$mon,$year);
				echo  $matter;
				$daye++;
            }
			else
			{
				echo  "<td style='background-color: $bgcolor'></td>";
            }
            $dd++;
        }
		echo "</tr>";
	}
	if($noOfRows==5)
		echo "<tr  style='background-color: $bgcolor'><td colspan='7'>&nbsp;</td></tr>";
	?>
</table> 
