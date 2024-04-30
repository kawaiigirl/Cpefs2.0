<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead(""," <link rel='stylesheet' href='include/view/lightbox.css'>");

AddHeader_StartMain(GetNavLinks());
?>
<div class="row">
    <h1 class="header"><button class="prev-unit" onClick="window.location.href='unit_details.php?id=<?=getPreviousUnit($_GET['id'])?>'">&#10094;</button>
        <?=show_text($booking['unit_name']) ?>
        <button class="next-unit" onClick="window.location.href='unit_details.php?id=<?=getNextUnit($_GET['id'])?>'">&#10095;</button></h1>

    <div class="card">
        <div id="myModal">
            <div id="images" class="slideshow-container">
                <span id="closeButton" style="display: none;" class="close cursor" onclick="closeModal()">&times;</span>
                <?php
                $numberOfImages = getNumberOfImagesForUnit($_GET['id']);
                $name = getUnitName($_GET['id']);
                for ($i = 1; $i <= $numberOfImages; $i++)
                {
                    $captionText = "";
                    echo "<div class='mySlides fade'>
                    <div class='numberText'>$i/$numberOfImages</div>";
                    echo " <img src='include/view/images/units/" . $name . "/" . $i . ".jpg'";
                    echo " onclick='openModal($i)'";
                    echo "></div>";
                }
                ?>
                <!-- Next and previous buttons -->
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
        </div>
    </div>
    <div class="evenColumns">
        <div class="card">
            <p><?=show_text($booking['unit_location']) ?></p>
            <p>Basic - <?="$" . $booking['basic_rate'] ?><br>
                Peak - <?="$" . $booking['peak_rate'] ?><br>
                Weekends - <?="$" . $booking['weekend_rate'] ?></p>
            <ul>
                <?=$booking['description'] ?>
            </ul>
            <strong><a class="nav-link book-now" href="make_booking.php?id=<?=$row['unit_id']?>">Book Now</a>
               </strong>
        </div>
    </div>
    <div class="evenColumns">
        <div class="card">
                <h2>Unit Availability</h2>
                <?php include "calendar.php"; ?>
                <p><span class="Green"><strong>Pending</strong></span> - booking to be confirmed<br>
                    <span class="Blue">Booked</span></p>
                <p>All units are a minimum 3 night stay.</p>
        </div>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
<script language="javascript" src="include/view/lightbox.js"></script>
</html>