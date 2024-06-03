<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<div class="row"><h1 class="header">Past Bookings</h1>
    <div class="singleColumn">
        <div class="card" style="text-align: center"><a href="my_bookings.php" class="nav-link link-button"><< Back to Current Bookings</a></div>
        <?php
        if(count($displayPastBookings)>0)
        {
            ?>
            <?php
            foreach($displayPastBookings as $booking)
            {
                DisplayBooking($booking,true);
            }
        }

        ?>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>