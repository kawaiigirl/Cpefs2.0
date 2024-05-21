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
        <form name='form1' method='post' action='https://www.stratapay.com.au/paypage.aspx'">
        <?php
        if(count($displayBookings)>0)
        {
            foreach($displayBookings as $booking)
            {
                ?>
                <div class="card">
                    <h2 class="header" style="text-align: left"><?=$booking['unit_name']?></h2>
                    <div class="row" style="padding:0;">
                    <div class="leftColumn" style="width: 45%; padding-left: 0">
                        <div class="row"><span class="leftData bookingDetailsLabel"><strong>Invoice Number:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['invoice_number']?></span></div>
                        <div class="row" style="background-color: #e3e3e3"><span class="leftData bookingDetailsLabel"><strong>Check-in Date:</strong></span><span class="rightData bookingDetailsLabel"><?=date("d M Y",strtotime($booking['check_in_date']));?></span></div>
                        <div class="row"><span class="leftData bookingDetailsLabel"><strong>Deposit Paid:</strong></span><span class="rightData bookingDetailsLabel"><?="$".$booking["paid"]?></span></div>
                        <div class="row" style="background-color: #e3e3e3"><span class="leftData bookingDetailsLabel"> <strong>Balance Paid:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['balance_paid']?></span></div>
                    </div>
                    <div class="rightColumn" style="width: 45%;padding: 0;">
                        <div class="row"><span class="leftData bookingDetailsLabel"><strong>Balance Due On:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['date_due']?></span></div>
                        <div class="row" style="background-color: #e3e3e3"><span class="leftData bookingDetailsLabel"><strong>Total Due:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['total_due']?></span></div>
                            <div class="row"><span class="leftData bookingDetailsLabel"><strong>Payments Made:</strong></span>
                                <span class="rightData bookingDetailsLabel"><a class="nav-link book-now" href="booking_payments.php?id=<?=$booking['booking_id']?>"> See Payments</a>
                                    </span></div>
                                <div class="row" style="background-color: #e3e3e3"><span class="leftData bookingDetailsLabel"><strong>Booking Status:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['booking_status']?></span></div>
                    </div>
                        <div class="row" style="padding:0;">
                            <div class="leftColumn" style="width: 45%; padding-left: 0">
                                <?=$booking['payment_amount']?><br>
                            </div>
                            <div class="rightColumn" style="width: 45%;padding: 0;">
                        <?php
                        if(($booking['rate']>$booking['paid']) &&($booking["approve"]!=0)&&($booking["approve"]!=3))
                        { ?>
                            <input style="margin-top: 6px;" type="submit" value="Pay Now" onClick="populateForm(<?=$booking['booking_id']?>)">
                            <?php
                        } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        else
        {
            echo '<div class="card"><span style="color: red">You have no current booking</span></div>';
        }
        ?>
            <input type="hidden" id='stringStrataPayNumber' name='stringStrataPayNumber' value='' />
            <input type="hidden" name='decimalAmount' id='decimalAmount' value='' />
            <!-- <input type=hidden name='stringText' value='Editable comment'/> -->
            <input type="hidden" name='returnToPage' value='<?php echo SITE_URL?>/return.php' />
            <input type="hidden" name='v1' id='v1' value='' />
            <input type="hidden" name='v2' id='v2' value='' />
        </form>
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