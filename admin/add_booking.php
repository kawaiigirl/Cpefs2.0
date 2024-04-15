<?php
include "include/admin_header.php";
include_once "../inc/calendar.php";
include("redirect_to_adminlogin.php");

global $dbc;
$focusOnError=0;
$availabilityMsg =null;
$errorMessages=$validationArray = array();
$foundErrors = false;
$member_id=$unit_id = $firstname = $lastname = $email = $phone = $check_in_date= "";
$nights = $weekend_rate = $basic_rate= $peak_rate =$paid= 0;

$rate=null;
//inputing member information into the form
if(!IsPostSetAndNotEmpty('member_id') && IsGetSetAndNotEmpty('member_id'))
{
	$row=$dbc->getSingleRow("Select * from cpefs_members where member_id=?",__LINE__,__FILE__,array("i",&$_GET['member_id']));
	$member_id=show_text($row['member_id']);
	$firstname=show_text($row['member_firstname']);
	$lastname=show_text($row['member_lastname']);
	$email=show_text($row['member_email']);
	$phone=show_text($row['member_telephone']);
}
else
{
    $member_id = setFromPostOrGet('member_id');
    $firstname = setFromPostOrGet('firstname');
    $lastname = setFromPostOrGet('lastname');
    $email = setFromPostOrGet('email');
    $phone = setFromPostOrGet('phone');
}
//setting variables from post or get
$unit_id = setFromPostOrGet('unit_id');
$check_in_date = setFromPostOrGet('check_in_date');
$nights = setFromPostOrGet('nights');
$rate = setFromPostOrGet('rate');
$paid = setFromPostOrGet('paid');

//Checking if Available and updating Rates if not already set
if($check_in_date!="" && $unit_id!="" &&$nights!="")
{
    //$check_in_date=date("Y-m-d",strtotime($check_in_date));

    //Check if date is available
    $availabilityMsg = Booking::getAvailabilityDateList($unit_id,$check_in_date,$nights);

    //set rate if not already set
    if(($rate =="" || $rate ==0) && !IsGetSetAndNotEmpty('id'))
    {
        $rate = getUnitStandardRate($unit_id, $check_in_date, $nights);
    }
}
//Form Submitted validating, creating/updating booking
if(IsPostSetAndEquals('submit', "Submit"))
{

    $validationArray = Booking::validateInput($member_id, $unit_id, $firstname, $lastname, $email, $phone, $check_in_date, $nights);
    $focusOnError = $validationArray['focusOnError'];
    $errorMessages = $validationArray['errMsg'];

	if($focusOnError == "")
	{
	    $booking = new Booking($member_id,$unit_id,$firstname,$lastname,$email,$phone,$check_in_date,$nights);
	    if(IsGetSetAndNotEmpty('id'))
            $booking->updateBooking($_GET['id'],$rate);
	    else
	    	$booking->addManualBooking($rate,$paid);
    }
	else
        $foundErrors = true;
}
//Retrieving booking information and displaying it to edit
if(count($_POST)<=0 && IsGetSetAndNotEmpty('id'))
{
	$row=$dbc->getSingleRow("Select * from cpefs_booking where booking_id=?",__LINE__,__FILE__,array("i",&$_GET['id']));
	$member_id=show_text($row['member_id']);
	if($unit_id == "")
	    $unit_id=show_text($row['unit_id']);
	$firstname=show_text($row['firstname']);
	$lastname=show_text($row['lastname']);
	$email=show_text($row['email']);
	$phone=show_text($row['phone']);
	$nights=show_text($row['nights']);
	$check_in_date=date("Y-m-d",strtotime($row['check_in_date']));
	$rate=$row['rate'];
	$paid=$row['paid'];
}

?>
<script>

function selectBoxOnChange()
{
    let member_id = document.getElementById('member_id');
    let unit_id = document.getElementById('unit_id');
    let nights = document.getElementById('nights');
    let check_in_date = document.getElementById('check_in_date');
    let booking_id = document.getElementById('booking_id');
    if(booking_id.value !=='')
        window.location.href='add_booking.php?id='+booking_id.value+'&member_id='+ member_id.options[member_id.selectedIndex].value+'&unit_id='+unit_id.options[unit_id.selectedIndex].value+'&nights='+nights.value+'&check_in_date='+check_in_date.value;
    else
        window.location.href='add_booking.php?member_id='+ member_id.options[member_id.selectedIndex].value+'&unit_id='+unit_id.options[unit_id.selectedIndex].value+'&nights='+nights.value+'&check_in_date='+check_in_date.value;
}
</script>

<table width="98%" height="100%" cellpadding="0"  cellspacing="0">
  <tr>
    <td colspan="3"><table width="100%"  cellspacing="0" cellpadding="0">
        <tr>
          <td class="body_head1">&nbsp;</td>

        <td align="center" class="body_bg1">Add/Edit Booking</td>
          <td class="body_head2">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#FFA3AB"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    <td height="360" width="100%" align="center" valign="middle">
		 <form name="frm" id="frm" action="" method="post" >
		<table width="60%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">

		  <tr>
			<td align="left" valign="top"><table width="100%" cellpadding="3"  cellspacing="0" bordercolor="#CCD5E6">
              <tr class="bgc">
                <td width="47%" height="30" align="right" valign="middle" class="label_name1"><span style='color:red'>*</span>Member
                  :</td>
                <td width="53%" align="left" valign="middle">
                    <input type='hidden' name='booking_id' id='booking_id' value='<?php if(IsGetSetAndNotEmpty('id')) echo $_GET['id']?>'>
                    <select id='member_id' name="member_id" onchange="selectBoxOnChange()">
				<option value = "null">
				</option>
                    <?php
				 $re=$dbc->getResult("Select * From cpefs_members Where member_status=2 ORDER BY member_name",__LINE__,__FILE__);
				 while($ro=mysqli_fetch_array($re))
				 {
				 ?>
                    <option value="<?php echo $ro['member_id']?>" <?php if($member_id == $ro['member_id'] )echo"Selected"; ?>>
                    <?php echo $ro['member_name']?>
                    </option>
                    <?php
				 }
				 ?>
                    </select> <span class='error'><?php if($foundErrors) echo $errorMessages['member_id'];?></span></td>
              </tr>
              <tr class="bgc1">
                <td align="right" height="30" valign="middle" class="label_name1"><span style='color:red'>*</span>Unit
                  : </td>
                <td align="left" valign="middle"> <select name="unit_id" id="unit_id" onchange="selectBoxOnChange()">
                    <option <?php if($unit_id =="")echo "Selected";?> value="">
                        Please select
                    </option>
                    <?php
                    $res=getUnits();
                    while($row=$res->fetch_array(MYSQLI_ASSOC))
                    {
                    ?>
                        <option <?php if($unit_id==$row['unit_id'])echo "Selected";?> value="<?php echo $row['unit_id']?>">
                        <?php echo show_text($row['unit_name'])?>
                        </option>
                        <?php
                    }
                    ?>
                    </select><span class='error'><?php if($foundErrors) echo $errorMessages['unit_id'];?></span> </td>
              </tr>
			  
              <tr class="bgc">
                <td align="right" height="30" valign="middle" class="label_name1">First Name
                  : </td>
                <td align="left" valign="middle"><input type="text" name="firstname" id="firstname" value="<?php echo $firstname?>"><span class='error'><?php if($foundErrors) echo $errorMessages['firstname'];?></span></td>
              </tr>
			  <tr class="bgc1">
                <td align="right" height="30" valign="middle" class="label_name1">Last Name
                  : </td>
                <td align="left" valign="middle"><input type="text" name="lastname" id="lastname" value="<?php echo $lastname?>"><span class='error'><?php if($foundErrors) echo $errorMessages['lastname'];?></span></td>
              </tr>
              <tr class="bgc">
                <td align="right" height="30" valign="middle" class="label_name1">Email
                  : </td>
                <td align="left" valign="middle"><input type="text" name="email" id="email" value="<?php echo $email?>"><span class='error'><?php if($foundErrors) echo $errorMessages['email'];?></span></td>
              </tr>
              <tr class="bgc1">
                <td align="right" height="30" valign="middle" class="label_name1">Telephone
                  : </td>
                <td align="left" valign="middle"><input type="text" name="phone" id="phone" value="<?php echo $phone?>"><span class='error'><?php if($foundErrors) echo $errorMessages['phone'];?></span></td>
              </tr>
              <tr class="bgc">
                <td height="30" align="right" valign="middle" class="label_name1"><span style="color:red">*</span>Number
                  Of Nights :</td>
                <td align="left" valign="middle"><input name="nights" id="nights" value="<?php echo $nights?>" /><span class='error'><?php if($foundErrors) echo $errorMessages['nights'];?></span></td>
              </tr>
              <tr class="bgc1">
                <td align="right" height="30" valign="middle" class="label_name1"><span style="color:red">*</span>Check In Date : </td>
                <td align="left" valign="middle">
				  <input type="text" name="check_in_date" id="check_in_date" value="<?php echo $check_in_date?>" onfocus="popUpCalendar(this,document.frm.check_in_date,'yyyy-mm-dd');" />
				  <button name ='submit' type="submit" value='checkAvailable'>Check if Available</button><!-- onclick="window.location.href='add_booking.php?member_id='+member_id.options[member_id.selectedIndex].value+'&unit_id='+unit_id.options[unit_id.selectedIndex].value+'&check_in_date='+check_in_date.value+'&nights='+nights.value+'&rate='+rate.value;"> Check if Available</button>-->
                    <span class='error'><?php if($foundErrors) echo $errorMessages['check_in_date'];?></span></td>
              </tr>
			  <?php
			  if($availabilityMsg != '' )
			  {	
					echo "<tr><td></td><td>";
					echo $availabilityMsg;
					echo "</td></tr>";
			  }
			  ?>
			  <tr class="bgc">
                <td align="right" height="30" valign="middle" class="label_name1">Rate
                  : </td>
                <td class="header" align="left" valign="middle"><input type="text" name="rate" id="rate" value="<?php echo $rate;?>">&nbsp;USD</td>
              </tr>
			  <?php
			  if(!IsGetSetAndNotEmpty('id'))
			  {
			  ?>
			  <tr class="bgc1">
                <td align="right" height="30" valign="middle" class="label_name1">Paid
                  : </td>
                <td class="header" align="left" valign="middle"><input type="text" name="paid" id="paid" value="<?php echo $paid?>">&nbsp;USD</td>
              </tr>
			  <?php
			  }
			  else
			  {?>
			  <tr class="bgc1">
                <td align="right" height="30" valign="middle" class="label_name1">Paid
                  : </td>
                <td class="header" align="left" valign="middle"><table cellpadding="3" cellspacing="0"><tr class="bgc1"><td class="header" height="30" width="80"><?php echo $paid?>&nbsp;USD</td><td class="header"><a href="booking_payments.php?id=<?php echo $_GET['id']?>">Go to Manage Payments</a><br/>
				<span class="error">If you need to update paid, you can plus or minus values when managing payments</span></td></tr></table></td>
              </tr>
			 <?php }
			  ?>
              <tr>
                <td height="30" colspan="2" align="center" valign="middle" > 
				<?php
				if($_SESSION['IS_ADMIN']==1)
					echo '<input name="submit" type="submit" class="button1" value="Submit" />';
				?>
                 &nbsp;&nbsp; <input name="back" type="button" class="button1" value="Back" onClick="window.location.href='booking_listing.php'"></td>
              </tr>
            </table></td>
		</tr>

</table>
        </form>
<?php
set_focus($focusOnError);

	include "include/admin_footer.php";
?>

