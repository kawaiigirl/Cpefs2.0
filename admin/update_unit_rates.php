<?php
include "include/admin_header.php"; 
include_once "redirect_to_adminlogin.php";
require_once "../include/validator.php";

$f=0;
$err=0;
$validator=new Validator;
$_SESSION['backpage']="unit_listing.php";
global $dbc;
if(isset($_POST['submit']))
{
	if(isset($_POST['id']) && $_POST['id'] !="" )
	{
		$i = 0;
		foreach($_POST['id'] as $id)
		{ 
			
			if( $_POST['new_basic_rate'][$i] == "" )
			{
			$f = 1;
			
			}
			if (!$validator->Number($_POST['new_basic_rate'][$i],'New Basic Rates must be Numeric'))
			{
			$err = 1;
			}
			if($_POST['new_peak_rate'][$i] == "")
			{
			$f = 1;
			
			}
			if (!$validator->Number($_POST['new_peak_rate'][$i],'New Peak Rates must be Numeric'))
			{
			$err = 1;
			}
			if($_POST['new_weekend_rate'][$i] == "")
				{
			$f = 1;
			
			}
			if (!$validator->Number($_POST['new_weekend_rate'][$i],' New Weekend Rates must be Numeric'))
			{
			$err = 1;
			}
			$i += 1; 
		}
		$i = 0;
		if ($f ==0 && $err == 0)
		{
			foreach($_POST['id'] as $id )
			{
				$basic = $_POST['new_basic_rate'][$i];
				$peak = $_POST['new_peak_rate'][$i];
				$weekend = $_POST['new_weekend_rate'][$i];
					$dbc->update("UPDATE cpefs_units Set basic_rate = ?, peak_rate = ? , weekend_rate = ? Where unit_id = ?",__LINE__,__FILE__,array("dddi",&$basic,&$peak,&$weekend,&$id));
				$i += 1; 
			}
			header("Location: update_booking_rates.php");
			exit;
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
		<td height="100%" width="100%" align="center" valign="top">
				<?php
				
			if($f == 1)
			{
			
		?>
		<table width="95%" border="0" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
		  <tr>
		  	<td>
				<span style="color: red">All input fields must be completed!</span>
				
			</td>
		</tr>
		</table>
		<?php
			}
			if($validator->foundErrors())
			{
			?>
			<table width="95%" border="0" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
		  <tr>
		  	<td>
				<?php $validator->listErrors(); ?>
				
			</td>
		</tr>
		</table>
			<?php }
		?>
    <form action="" method="post" name="frm" id="frm" enctype="multipart/form-data">
		<table width="95%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse;margin-top:10px;" align="center">
				<table width="95%" border="1" cellpadding="4" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
				<tr>
				<td width="10%" align="center" class="heading">Unit Name :</td>
				<td width="10%" align="center" class="heading">Current Basic Rate : </td>
				<td width="10%" align="center" class="heading"><span style='color:red'>*</span>New Basic Rate : </td>
				<td width="10%" align="center" class="heading">Current Peak Rate : </td>
				<td width="10%" align="center" class="heading"><span style='color:red'>*</span>New Peak Rate : </td>
				<td width="10%" align="center" class="heading">Current Weekend Rate : </td>
				<td width="10%" align="center" class="heading"><span style='color:red'>*</span>New Weekend Rate : </td>
										  
				</tr>
					<?php

						$qry="Select * from cpefs_units";

						$rows=$dbc->getNumRows($qry,__LINE__,__FILE__);
						if($rows>0)
						{		$i = 0;
								$res=$dbc->getResult($qry,__LINE__,__FILE__);
								$color = "bgc2";
								while($row=$res->fetch_array(MYSQLI_ASSOC))
								{
									
									
									if($color=="bgc")
												$color="bgc2";
											else 
												$color="bgc";
											?>
							<tr class="<?php echo $color?>">
										
								
										<td align="center" style="padding-left:10px;padding-right:10px;"> 
											<label>
												<?php echo show_text($row['unit_name'])?>

										    </label>
										</td>
										<td align="center" style="padding-left:10px;padding-right:10px;"><?php echo show_text($row['basic_rate'])?> </td>
										<td align="center" style="padding-left:10px;padding-right:10px;"> 
										<input name="new_basic_rate[]"  maxlength="20" type="text" class="inptbox"  value="<?php if(isset($_POST["new_basic_rate"][$i])) echo $_POST["new_basic_rate"][$i]?>"></td>
										<td align="center" style="padding-left:10px;padding-right:10px;"> <?php echo show_text($row['peak_rate'])?></td>
										<td align="center" style="padding-left:10px;padding-right:10px;"> 
										<input name="new_peak_rate[]" maxlength="20" type="text" class="inptbox"  value="<?php if(isset($_POST["new_peak_rate"][$i])) echo $_POST["new_peak_rate"][$i]?>"></td>
										<td align="center" style="padding-left:10px;padding-right:10px;"> <?php echo show_text($row['weekend_rate'])?></td>
										<td align="center" style="padding-left:10px;padding-right:10px;"> 
										<input name="new_weekend_rate[]"  maxlength="20" type="text" class="inptbox"  value="<?php if(isset($_POST["new_weekend_rate"][$i])) echo $_POST["new_weekend_rate"][$i]?>"></td>
									<input type="hidden" name="id[]" value="<?php echo $row['unit_id']?>" />
									
							
						</tr>
						<?php 		$i += 1;}
						}	
						?>
						
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
</table>
    </form>
<?php
	include "include/admin_footer.php";
?>