<?php
include "include/admin_header.php";
include_once "../include/common.php";
include "../include/calendar.php";
global $dbc;
$f=0;
$errors= [];
if(isset($_POST['submit']) && $_POST['submit']=="Submit")
{
	if($_POST['start_date']=="" )
	{
		$errors[]="Please Specify Start Date of Period";
		$f=1;
	}

	if($_POST['end_date']=="" )
	{
		$errors[]="Please Specify End Date of Period";
		$f=2;
	}

/*	echo strtotime( $_POST['end_date']);
	echo "<br>";
	echo "<br>";
	echo strtotime( $_POST['start_date']);
	echo "<br>";
*/
	$tm_start_date = strtotime( $_POST['start_date']);
	$tm_end_date = strtotime( $_POST['end_date']);

	if($f==0)
	{
		$day=date("D",$tm_start_date);
		if($day!="Mon")
		{
			$errors[]="Start Date Must be a Monday";
			$f=1;
		}
		
		$day=date("D",$tm_end_date);
		if($day!="Sun")
		{
			$errors[]="End Date Must be a Sunday";
			$f=1;
		}
	}
	
	if($f==0)
	{
		if($tm_start_date >=$tm_end_date)
		{
			$errors[]="Start Date Must be Greater than End Date";
			$f=1;
		}
	}
	if($f==0)
	{
		$qry = " peak_period_start_date =?";
		$qry .= ", peak_period_end_date =?";
		
		if($_GET['id']=="")
		{
			
			$dbc->insert("Insert into cpefs_peak_periods set $qry",__LINE__,__FILE__,array("ss",&$_POST["start_date"] ,& $_POST["end_date"]));
			header("Location: peak_period_listing.php?opt=1");
        }
		else
		{
			$dbc->update(" Update cpefs_peak_periods set $qry where peak_period_id=?",__LINE__,__FILE__,array("ssi",&$_POST["start_date"] ,& $_POST["end_date"],&$_GET['id']));
			header("Location: peak_period_listing.php?opt=2");
        }
        exit;
    }
}
if(count($_POST)<=0 && isset($_GET['id']) && $_GET['id']!="")
{
	$row=$dbc->getSingleRow("Select * from cpefs_peak_periods where peak_period_id=?",__LINE__,__FILE__,array("i",& $_GET['id']));
	$_POST['start_date']=$row['peak_period_start_date'];
	$_POST['end_date']=$row['peak_period_end_date'];

	$_SESSION['backpage']="peak_period_listing.php#$_GET[id]";

}


?>

    <table width="98%" height="100%" cellpadding="0"  cellspacing="0">
  <tr>
    <td colspan="3"><table width="100%"  cellspacing="0" cellpadding="0">
        <tr>
          <td class="body_head1">&nbsp;</td>
          <td align="center" class="body_bg1">Manage Peak Periods</td>
          <td class="body_head2">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#FFA3AB"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    <td height="360" width="100%" align="center" valign="top" style="padding-top:50px;">
		  <?php
			if($f>0 && $errors>0)
			{
			?>
		<table width="35%" border="0" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
              <tr>
                <td  class="error"><?php
							  foreach($errors as $error)
							  {
								echo "<li>$error</li>";
							  }
						  ?>
                  <br />
                  <br />
                </td>
              </tr>
		</table>
		<?php
			}
		?>
<form name="frm" action="" method="post" >
<table width="35%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">

		  <tr>
			<td align="left" valign="top"><table width="100%" cellpadding="0"  cellspacing="0" bordercolor="#CCD5E6">
              <tr class="bgc"> 
                <td height="30" align="right" valign="middle" class="label_name1"><span style='color:red'>*</span>Start Date(Mon)
                  :</td>
                <td align="left" valign="middle"> <input name="start_date"  class="textbox" id="start_date" type="text" onkeypress="return false;" onfocus='popUpCalendar(this,document.frm.start_date,"yyyy-mm-dd")' value="<?php if(isset($_POST["start_date"])) echo $_POST["start_date"]?>"></td>
              </tr>
              <tr class="bgc1"> 
                <td height="30" align="right" valign="middle" class="label_name1"><span style='color:red'>*</span>End Date(Sun)
                  :</td>
                <td align="left" valign="middle"> <input name="end_date"  class="textbox" id="end_date" type="text" onkeypress="return false;" onfocus='popUpCalendar(this,document.frm.end_date,"yyyy-mm-dd")' value="<?php if(isset($_POST["end_date"])) echo $_POST["end_date"]?>"></td>
              </tr>
              <tr class="bgc"> 
                <td height="30" colspan="2" align="center" valign="middle" > 
                  	<input name="submit" type="submit" class="button1" value="Submit">
					&nbsp;&nbsp; 
					<input name="back" type="button" class="button1" value="Back" onClick="window.location.href='<?php echo $_SESSION['backpage']?>'"></td>
              </tr>
            </table></td>
		</tr>
</table>
</form>
<?php
	include "include/admin_footer.php";
?>