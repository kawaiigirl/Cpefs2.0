<?php
include "include/admin_header.php";
include_once "redirect_to_adminlogin.php";

$_SESSION['backpage']="unit_listing.php";
global $dbc;

$f=0;

if(isset($_POST['submit']))
{

	if(isset($_POST['update']) && $_POST['update'] !="")
	{
		foreach($_POST['update'] as $update)
        {
			$qry="Select cpefs_booking.*,cpefs_members.member_name,cpefs_units.* From cpefs_booking 					  
					Inner Join cpefs_members On cpefs_members.member_id=cpefs_booking.member_id
					Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id
					Where 1 and booking_id = ?";

			$rows=$dbc->getNumRows($qry,__LINE__,__FILE__,array("i",& $update));
			if($rows>0)
			{
				$res=$dbc->getResult($qry,__LINE__,__FILE__,array("i",& $update));
				while($row=$res->fetch_array(MYSQLI_ASSOC))
				{
					if($row['nights']==14)
						$mult=2;
					else
						$mult=1;
					
					if($row['is_peak_period'] =='P')	// It is a peak period
					{
								if($row['nights']==14)
								{
									$new_rate=$row['basic_rate'] + $row['peak_rate'];
								}
								else
								{
									$new_rate=$row['peak_rate'];
								}
					}
					elseif($row["nights"]==3 || $row["nights"]==4)
						$new_rate=$row['weekend_rate'];
					else
						$new_rate=$mult * $row['basic_rate'];	
					$dbc->update("Update cpefs_booking Set rate = ? Where booking_id = ?",__LINE__,__FILE__,array("di",&$new_rate,&$update));
				}
			}
		}
	}
}
?>
<table width="98%" height="350" cellpadding="0"  cellspacing="0" border="0">
	<tr>
   		<td colspan="3">
			<table width="100%"  cellspacing="0" cellpadding="0">
				<tr>
			  		<td class="body_head1">&nbsp;</td>
        			<td align="center" class="body_bg1"><strong>Update Unit Rates</strong></td>
			  		<td class="body_head2">&nbsp;</td>
				</tr>
			</table>
		</td>
 	</tr>
 	<tr>
    	<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    	<td width="95%"  align="center" valign="top">
            <form action="" method="post">
			<table width="95%" height="30">

      			<tr>
        		  <td width="33%" valign="middle" class="heading">
				 Bookings Without Deposits
				  </td>
      			</tr>
   			</table></form></td>
	</tr>
	<tr>
		<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
		<td height="100%" width="100%" align="center" valign="top">
		<table width="95%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse;margin-top:10px;" align="center">
			<form action="" method="post" name="frm" id="frm">
	 		<table width="95%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse;margin-top:10px;" align="center">
	 			<tr>
	 				<td>
						<table width="100%" border="1" cellpadding="4" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
							<tr>
							  <td width="15%" align="center" class="heading" style="padding-left:10px;">Member Name</td>
							  <td width="10%" align="center" class="heading">Unit Name </td>
							  <td width="15%" align="center" class="heading">Check-in Date</td>
							  <td width="10%" align="center" class="heading">Nights</td>
							  <td width="10%" align="center" class="heading">Peak/Standard Period</td>
							   <td width="10%" align="center" class="heading">Current Rate</td>
							  <td width="10%" align="center" class="heading">New Rate</td>
							  <td width="10%"  align="center" class="heading">Update to New Rate</td>
						  </tr>
						   <?php

								$qry="Select cpefs_booking.*,cpefs_members.member_name,cpefs_units.* From cpefs_booking 					  
										Inner Join cpefs_members On cpefs_members.member_id=cpefs_booking.member_id
										Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id
										Where 1  and rate > 0 and paid = 0 and check_in_date >= now()";

								$rows=$dbc->getNumRows($qry,__LINE__,__FILE__);
								if($rows>0)
								{
										$res=$dbc->getResult($qry,__LINE__,__FILE__);
										$color = "bgc2";
										while($row=$res->fetch_array(MYSQLI_ASSOC))
										{
											if($row['nights']==14)
												$mult=2;
											else
												$mult=1;
											
											if($row['is_peak_period'] =='P')	// It is a peak period
											{
														if($row['nights']==14)
														{
															$new_rate=$row['basic_rate'] + $row['peak_rate'];
														}
														else
														{
															$new_rate=$row['peak_rate'];
														}
											}
											elseif($row["nights"]==3 || $row["nights"]==4)
												$new_rate=$row['weekend_rate'];
											else
												$new_rate=$mult * $row['basic_rate'];		
												
											if($color=="bgc")
												$color="bgc2";
											else 
												$color="bgc";
											?>
									<tr class="<?php echo $color?>">
										<td align="left" style="padding-left:10px;">
										<?php echo $row["member_name"]?>
										</td>
										<td align="center" style="padding-right:10px;">
										  <?php echo show_text($row["unit_name"])?>
										</td>
										<td align="center" style="padding-right:10px;">
										  <?php echo $row["check_in_date"]?>
										</td>
										<td align="center" style="padding-right:10px;">
										  <?php echo $row["nights"]?>
										</td>
										
										<td align="center" style="padding-left:10px;padding-right:10px;"> 
											<?php if ($row["is_peak_period"] == 'S') echo "Standard"; else echo "Peak";?>
										</td>
										<td align="center" style="padding-right:10px;">
										  <?php echo $row["rate"]?>
										</td>
										<td align="center" style="padding-right:10px;">
										  <?php echo $new_rate?>
										</td>
										<td align="center">
											
											<input name="update[]" type="checkbox" value="<?php echo $row['booking_id']?>" />
										</td>
									</tr>
								  <?php
								  }
								} 
								else
								{
								?>
								  <tr>
									 <td align="center" colspan="7" valign="middle" class="error" >
										<b>No Bookings need manual updating!</b>
									 </td>
								  </tr>
								<?php
								}
								?>
						  </table>
					   </td>
					 </tr>
				</table><br />
				<table width="100%">
				   <tr>
				   
				   </tr>
					   <tr align="center"> 
						<td width="100%" height="10" align="center" valign="bottom"> <input name="back" type="button" class="button1" value="Back" onClick="window.location.href='<?php echo $_SESSION['backpage']?>'">
						
						<?php
						if($_SESSION['IS_ADMIN']==1)
							echo '&nbsp;<input name="submit" type="submit" class="button1" value="Submit" />';
						?>
						</td>
						
					  </tr>
					</table>
							</form>
</table>
			
<?php
	include "include/admin_footer.php";
?>
