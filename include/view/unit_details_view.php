<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead(""," <link rel='stylesheet' href='include/view/lightbox.css'>");

AddHeader_StartMain(GetNavLinks());
?>
<div class="row">
    <h1 class="header"> <?= show_text($booking['unit_name']) ?></h1>
    <div class="card">
        <div id="myModal">
            <div id="images" class="slideshow-container">
                <span id="closeButton" style="display: none;" class="close cursor" onclick="closeModal()">&times;</span>
                <?php
                $numberOfImages = 7;
                switch ($_GET['id'])
                {
                    case '1':
                        $name = "beachhaven";
                        $numberOfImages = 20;
                        break;
                    case '2':
                        $name = "cocobay";
                        $numberOfImages = 20;
                        break;
                    case '3':
                        $name = "focus";
                        $numberOfImages = 20;
                        break;
                    case '5':
                        $name = "peninsular";
                        $numberOfImages = 7;
                        break;
                }
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
            <p><?php echo show_text($booking['unit_location']) ?></p>
            <p>Basic - <?php echo "$" . $booking['basic_rate'] ?><br>
                Peak - <?php echo "$" . $booking['peak_rate'] ?><br>
                Weekends - <?php echo "$" . $booking['weekend_rate'] ?></p>
            <ul>
                <?php echo $booking['description'] ?>
            </ul>
            <p><img src="include/view/images/book_now.jpg" alt="Book Now" width="71" height="17" hspace="0" vspace="0"
                        onClick="window.location.href='make_booking.php?id=<?php echo $booking['unit_id'] ?>'"></p>
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