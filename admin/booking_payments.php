<?php 
include "include/admin_header.php";
include_once "../include/calendar.php";
include_once "../include/common.php";
include_once "redirect_to_adminlogin.php";
include_once "../include/validator.php";
$_SESSION['backpage']="booking_listing.php";
global $dbc;
$f=0;
$msg = "";
$validator=new Validator;

if(isset($_POST['Submit']))
{
	if(!$validator->Number($_POST['amount'],'Amount must be Numeric'))
	{
		$msg.= "Transaction amount must be Numeric";
	}
	if(!$validator->foundErrors())
	{
		makePayment($_POST['booking_id'],'Administration',$_POST['amount'],$_POST['comment']);
		$msg .= "Payment added<br/>";

        $qry = "Select confirm_date from cpefs_booking where booking_id = :booking_id";
		$result = $dbc->getSingleDataPDO($qry,__LINE__,__FILE__,array('booking_id'=>$_POST['booking_id']));
		$ssql="Select cpefs_booking.*,cpefs_members.*,cpefs_units.unit_name,cpefs_units.unit_location, cpefs_units.manager_email From cpefs_booking 
			   Inner Join cpefs_members On cpefs_members.member_id=cpefs_booking.member_id 
			   Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id Where booking_id=?";
			   
		$data=$dbc->getSingleRow($ssql,__LINE__,__FILE__,array("i",&$_POST['booking_id']));
		if($result == NULL)
		{
			$msg .= confirmBooking($_POST['booking_id'],$data,'booking_payments');
			$msg.="<br/>Booking Successfully Confirmed";
		}
		if($data['rate']==$data['paid'])	// Full payment is done and confirmmed
		{
			$msg .="<br>";
			$msg .= sendFullPaymentBookingEmail($_POST['booking_id'],$data,'booking_payments');
		}
	}	
}

$qry="Select * from cpefs_payments Where booking_id = ?";
$rows=$dbc->getNumRows($qry,__LINE__,__FILE__,array("i",&$_GET['id']));

$qry2="Select cpefs_booking.*,cpefs_members.member_name,cpefs_units.unit_name From cpefs_booking 					  
					Inner Join cpefs_members On cpefs_members.member_id=cpefs_booking.member_id
					Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id
					Where cpefs_booking.booking_id = ? ";
					
$row = $dbc->getSingleRow($qry2,__LINE__,__FILE__,array("i",&$_GET['id']));

?>
<table width="98%" height="350" cellpadding="0"  cellspacing="0" border="0">
	<tr>
		<td colspan="3">
			<a href="booking_listing.php"><< Back to Bookings</a>
			<table width="100%"  cellspacing="0" cellpadding="0">
				<tr>
					<td class="body_head1">&nbsp;</td>
					<td align="center" class="body_bg1"><strong>Payments Made for Booking ID <?php echo $_GET["id"];?></strong></td>
					<td class="body_head2">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
 	<tr>
    	<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    	<td width="99%"  align="center" valign="top"></td>
		<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
	</tr>
	<tr>
		<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
		<td height="100%" width="100%" align="center" valign="top">
			<table width="99%">
				<tr align="center"> 
					<td width="57%" height="10" align="right" valign="bottom">
					
					</td>
					
				</tr>
			</table>
	 		<table width="99%" border="0" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse;margin-top:10px;" align="center">
				<tr>
				<td>
				<table width="600" align="center">
					<tr class="bgc">
					<th width="50%" align="right" class="heading">Member:&nbsp;</th>
					<td width="50%" align="left" valign="middle"> &nbsp;<?php echo show_text($row["member_name"]);?></td>
					</tr>
					<tr class="bgc2">
					<th align="right" class="heading">Unit:&nbsp;</th>
					<td align="left" valign="middle">&nbsp;<?php echo show_text($row["unit_name"]);?></td>
					</tr>
					<tr class="bgc">
					<th align="right" class="heading">Check In Date:&nbsp;</th>
					<td align="left" valign="middle">&nbsp;<?php echo show_text($row["check_in_date"]);?></td>
					</tr>
					<tr class="bgc2">
					<th align="right" class="heading">Rate:&nbsp;</th>
					<td align="left" valign="middle">&nbsp;<?php echo "$".show_text($row["rate"]);?></td>
					</tr>
					<tr class="bgc">
					<th align="right" class="heading">Paid:&nbsp;</th>
					<td align="left" valign="middle">&nbsp;<?php echo "$".show_text($row["paid"]);?></td>
					</tr>
					<tr class="bgc2">
					<th align="right" class="heading">Balance Owing:&nbsp;</th>
					<td align="left" valign="middle">&nbsp;<?php echo "$"; echo $row["rate"] - $row["paid"]; ?></td>
					</tr>
					<tr>
					<td colspan="12" align="center">
					<form action="booking_payments.php?id=<?php echo $_GET["id"];?>" method="post">
						Add New Transaction: $<input type="text" name="amount"> 
						Comment:<input type="text" name="comment"> 
						<input type="hidden" name="booking_id" value="<?php echo $_GET["id"];?>">
						<input type="submit" name="Submit" value="submit">
					</form>
					</td>
					</tr>
					<?php if($msg != "")
					{
					?>
					<tr>
					<td colspan="12" align="center" ><span class="error">
							<?php echo $msg?></span></td><?php
					}	
					?>
					</tr>
					<tr>
					<td colspan="12" align="center">
							<i>Negative transactions are permitted. Comment is Optional (E.g. Add New Transaction: -100. Comment: Refund)</i>
					</td>
					</tr>
				</table>
				</td>
				</tr>
				<tr>
				<td>
				<table width="100%" border="0" cellpadding="7" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
				<tr>
                <td width="7%" align="center" class="heading">Payment ID</td>
                <td width="7%" align="center" class="heading">Date</td>
				<td width="7%" align="center" class="heading">Time</td>
                <td width="5%" align="center" class="heading">Payment Method</td>
				<td width="7%" align="center" class="heading">Amount</td>
				<td width="7%" align="center" class="heading">Comment</td>
				</tr>
				<?php
				if($rows>0)
				{
					$res = $dbc->getResult($qry,__LINE__,__FILE__,array("i",&$_GET['id']));
					$color="";
					while($row=mysqli_fetch_array($res))
					{
						if($color=="bgc")
							$color="bgc2";
						else 
							$color="bgc";
						?>
					  <tr class="<?php echo $color?>"> 
						<td align="center" style="padding-left:2px;"> 
						  <?php echo show_text($row["payment_id"])?>
						</td>
						<td align="center" style="padding-left:2px;">
							<?php echo date("d M Y",strtotime($row["date"]));?>
						   </td>
						<td align="center" style="padding-left:2px;">
							<?php echo date("h:ia",strtotime($row["date"]));?>
						   </td>
							<td align="center" style="padding-left:2px;">
						  <?php echo show_text($row["payment_method"])?>
						</td>
						<td align="center" style="padding-left:2px;">
						 <?php echo "$".$row["amount"]?>
						</td>
						<td align="center" style="padding-left:2px;">
							<?php echo $row["comment"]?>
						</td>
					  </tr>
					<?php
					}
				} 
				else
				{
				?>
				  <tr> 
					<td align="center" colspan="12" height="100" valign="middle" class="error" > 
					  <b>No Payment Records!</b> </td>
				  </tr>
				<?php
				}
				?>
			  <tr> 
				<td align="center" colspan="12" height="50" valign="middle" class="error" > 
				 Payments made before 18/12/2013 do not appear in this section but are still reflected in the amount paid. </td>
			  </tr>
			</table>
			</td>
		</tr>
		</table>
		<br />
		   <table width="99%">
			 <tr align="center"> 
			  <td width="57%" height="10" align="right" valign="bottom">
			  </td>
			  
			  </tr>
			</table>	
		</td>
	</tr>
<?php include "include/admin_footer.php";?>