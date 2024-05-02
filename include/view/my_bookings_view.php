<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<div class="row"><h1 class="header">My Bookings</h1>
    <div class="card">
		<p><strong>Payment options</strong></p>
		<p>All payments can be made  by <span class="style1">direct deposit (preferred method)</span> or credit card.<br />
		<br />
		<strong>Direct deposit</strong><br />
		Account name: Castlemaine Perkins Employee Friendly Society<br />
		BSB: 064123<br />
		Account number: 10012729<br />
		<strong>IMPORTANT Please use your invoice number as the reference so that we can match your payment with your booking</strong><br />
		<br />
		<strong>Credit card</strong><br />
		Use the 'Pay Now' feature below<br />
		<span class="style1">Payment by credit card will have a processing fee added to the amount charged to your credit card.</span>
		</p>
    </div>
    <div class="card">
	<form name='form1' method='post' action='https://www.stratapay.com.au/paypage.aspx' onSubmit="return populateForm(); ">
    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <tr>
			<td width="15%" height="25" align="center" valign="middle" class='heading'><strong>Unit</strong></td>
			<td width="11%" height="25" align="center" valign="middle" class='heading'><strong>Invoice No.</strong></td>
			<td width="11%" height="25" align="center" valign="middle" class='heading'><strong>Check In</strong></td>
			<td width="13%" height="25" align="center" valign="middle" class='heading'><strong>Deposit Paid</strong></td>
			<td width="12%" height="25" align="center" valign="middle" class='heading'><strong>Balance Due</strong></td>
			<td width="13%" height="25" align="center" valign="middle" class='heading'><strong>Balance Paid</strong></td>
			<td width="11%" height="25" align="center" valign="middle" class='heading'><strong>Total Due</strong></td>
			<td width="15%" height="25" align="center" valign="middle" class='heading'><strong>Payments Made</strong></td>
			<td width="15%" height="25" align="center" valign="middle" class='heading'><strong>Booking Status</strong></td>
			<td width="10%" height="25" align="center" valign="middle" class='heading'><strong>Payment Amount</strong></td>
            <td width="10%" height="25" align="center" valign="middle" class='heading'><strong><label for="group1">Select Booking to Pay</label></strong></td>
        </tr>
		<?php

		if(count($bookings)>0)
		{
			foreach($bookings as $booking)
			{
			?>
			<tr>
				<td height="25" valign="top"><?php echo $booking['unit_name']?></td>
				<td height="25" align="center" valign="top"><?php echo $booking['invoice_number']?></td>
				<td height="25" align="center" valign="top"><?php echo date("d M Y",strtotime($booking['check_in_date']));?></td>
				<td height="25" align="center" valign="top">
				<?php echo "$".$booking["paid"]?>
				</td>
				<td height="25" align="center" valign="top">
                <?=$booking['date_due']?>
				</td>
				<td height="25" align="center" valign="top">
                <?=$booking['balance_paid']?>
				</td>
				<td height="25" align="center" valign="top">
                <?=$booking['total_due']?>
				</td>
				<td style="text-align: center;">
				<img src="admin/images/files.png" title="See Payments" onClick="window.location.href='booking_payments.php?id=<?php echo $booking['booking_id']?>'" style="cursor:pointer;">
				</td>
				<td height="25" align="center" valign="top">
                <?=$booking['booking_status']?>
				</td>
				<td height="25" align="center" valign="top">
                    <?=$booking['payment_amount']?>
				</td>
				<td align="center">
				<?php
				if(($booking['rate']>$booking['paid']) &&($booking["approve"]!=0)&&($booking["approve"]!=3))
				{ ?>
					<input type="radio" id="group1" name="group1" value="<?php echo $booking['booking_id']?>" onClick="populateForm()">
				<?php
				} ?>
				</td>
			</tr>
			<?php
			}
		}
		else
		   echo '<tr><td colspan="8" height="50" valign="middle" align="center"><span style="color: red">You have no current booking</span></td></tr>';
		?>
	</table>
		<table width="100%" border="1" cellspacing="0" cellpadding="5">
		<tr>
			<td align="center">
			<input type="hidden" id='stringStrataPayNumber' name='stringStrataPayNumber' value='' />
			<input type="hidden" name='decimalAmount' id='decimalAmount' value='' />
			<!-- <input type=hidden name='stringText' value='Editable comment'/> -->
			<input type="hidden" name='returnToPage' value='<?php echo SITE_URL?>/return.php' />
			<input type="hidden" name='v1' id='v1' value='' />
			<input type="hidden" name='v2' id='v2' value='' />
			<input type="image" src="images/PayNow.jpg" alt="Pay Now" style="margin-top:3px;">
			</td>
		</tr>
		</table>
	</form>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>

<script>
    function populateForm(){
        let clientStrataAccountId = 0<?php echo "+".STRATAPAYCLIENTNO;?>;
        let selection = document.form1.group1;
        let radioChecked = 0;

        if(selection == null)
            return false;

        if(selection.length == null)
        {
            if(selection.checked === true)
            {
                if(document.getElementById('decimalAmount' + selection.value).value === "select")
                {
                    document.getElementById('error_decimalAmount'+ selection.value).style.display = '';
                    setTimeout(function() { document.getElementById('decimalAmount'+ selection.value).focus(); }, 10);
                    return false;
                }
                document.getElementById('v2').value = document.getElementById('decimalAmount' + selection.value).value;
                document.getElementById('decimalAmount').value = document.getElementById('decimalAmount' + selection.value).value;


                <?php
                if(!LOCALHOST)
                {
                ?>
                document.getElementById('stringStrataPayNumber').value = clientStrataAccountId + document.getElementById('invoice' + selection.value).value;
                <?php
                }
                else{
                ?>
                ////////Stratapay test account value/////////
                document.getElementById('stringStrataPayNumber').value = clientStrataAccountId;

                document.getElementById('stringStrataPayNumber').type = 'text';//shows the client number to confirm localhost setting is active
                <?php
                }
                ?>
                document.getElementById('v1').value = selection.value;
                radioChecked = 1;
            }

        }
        else
        {
            //loops through all the checkboxes and finds the selected one
            for (let i=0; i<selection.length; i++)
                if (selection[i].checked === true)
                {
                    if(document.getElementById('decimalAmount' + selection[i].value).value === "select")
                    {
                        document.getElementById('error_decimalAmount'+ selection[i].value).style.display = '';
                        setTimeout(function() { document.getElementById('decimalAmount'+ selection[i].value).focus(); }, 10);
                        return false;
                    }
                    document.getElementById('v2').value = document.getElementById('decimalAmount' + selection[i].value).value;
                    document.getElementById('decimalAmount').value = document.getElementById('decimalAmount' + selection[i].value).value;

                    <?php
                    if(!LOCALHOST)
                    {
                    ?>
                    document.getElementById('stringStrataPayNumber').value = clientStrataAccountId + document.getElementById('invoice' + selection[i].value).value;
                    <?php
                    }
                    else{
                    ?>
                    ////////Stratapay test account value/////////
                    document.getElementById('stringStrataPayNumber').value = clientStrataAccountId;
                    document.getElementById('stringStrataPayNumber').type = 'text';//shows the client number to confirm localhost setting is active
                    <?php
                    }
                    ?>

                    document.getElementById('v1').value = selection[i].value;
                    radioChecked = 1;
                }
        }
        if(radioChecked === 0)
            return false;
    }

</script>
</html>