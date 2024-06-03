<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<div class="row"><h1 class="header">My Bookings</h1>
    <div class="singleColumn">
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

        <?php
        if(count($displayCurrentBookings)>0)
        {
            ?>
            <h2 class="header">Current Bookings</h2>
            <form name='form1' method='post' action='https://www.stratapay.com.au/paypage.aspx'>
        <?php
            foreach($displayCurrentBookings as $booking)
            {
                DisplayBooking($booking);
            }
            ?>
            <input type="hidden" id='stringStrataPayNumber' name='stringStrataPayNumber' value='' />
            <input type="hidden" name='decimalAmount' id='decimalAmount' value='' />
            <!-- <input type=hidden name='stringText' value='Editable comment'/> -->
            <input type="hidden" name='returnToPage' value='<?php echo SITE_URL?>/return.php' />
            <input type="hidden" name='v1' id='v1' value='' />
            <input type="hidden" name='v2' id='v2' value='' />
            </form>
        <?php
        }
        else
        {
            echo '<div class="card"><span style="color: red">You have no current bookings</span></div>';
        }
?>
        <?php
        if(count($pastBookings)>0)
        {?>
            <div class="card" style="text-align: center"><a href="past_bookings.php" class="nav-link link-button">View Past Bookings</a></div>

        <?php
        }
        ?>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>

<script>
    function populateForm(bookingID)
    {
        let clientStrataAccountId = 0<?php echo "+".STRATAPAYCLIENTNO;?>;
        document.getElementById('v2').value = document.getElementById('decimalAmount' +bookingID).value;
        document.getElementById('decimalAmount').value = document.getElementById('decimalAmount' + bookingID).value;
        document.getElementById('v1').value = bookingID;
        <?php
        if(!LOCALHOST)
        {
        ?>
            document.getElementById('stringStrataPayNumber').value = clientStrataAccountId + document.getElementById('invoice' + bookingID).value;
        <?php
        }
        else
        {
        ?>
            document.getElementById('stringStrataPayNumber').value = clientStrataAccountId;
            document.getElementById('stringStrataPayNumber').type = 'text';//shows the client number to confirm localhost setting is active
        <?php
        }
        ?>
    }
</script>
</html>