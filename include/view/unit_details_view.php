<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead(""," <link rel='stylesheet' href='include/view/lightbox.css'>");

AddHeader_StartMain(GetNavLinks());
?>
<div class="row"><h1 class="header">Units</h1>
    <div class="card">
            <h1>
                <?php echo show_text($booking['unit_name']) ?>
            </h1>
        <div class="slideshow-container">
            <!-- Full-width images with number and caption text -->
            <?php
            switch ($_GET['id'])
            {
                case '1':
                    $name = "beachhaven";
                    break;
                case '2': $name ="cocobay";
                    break;
                case '3': $name ="focus";
                    break;
                case '4': $name ="peninsular";
                    break;
            }
            $numberOfImages = 7;
            for($i=1;$i<=$numberOfImages;$i++)
            {
                $captionText="";
                echo "<div class='mySlides fade'>
                <div class='numbertext'>$i/ 3</div>";
                echo " <img src='include/view/images/units/".$name."0".$i.".jpg'  style='width:100%'>";
                echo "<div class='text'>$captionText</div></div>";
            }
            ?>
            <!-- Next and previous buttons -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
        <br>
        <!-- The dots/circles -->
        <div style="text-align:center">
        <?php
        for($i=1;$i<=$numberOfImages;$i++)
        {
            echo " <span class='dot' onclick='currentSlide($i)'></span>";
        }
        ?>
        </div>


            <p><?php echo show_text($booking['unit_location']) ?></p>
            <p>Basic - <?php echo "$" . $booking['basic_rate'] ?><br>
                Peak - <?php echo "$" . $booking['peak_rate'] ?><br>
                Weekends - <?php echo "$" . $booking['weekend_rate'] ?></p>
            <ul>
                <?php
                switch ($_GET['id'])
                {
                    case 1:
                        ?>
                        <li>Unit 15D two bedroom unit</li>
                        <li>Master&nbsp; bedroom&nbsp; with ensuite queen bed TV and balcony</li>
                        <li> 2nd bedroom&nbsp; 2 x single beds</li>
                        <li>Second bathroom with bath</li>
                        <li>Laundry auto washer and dryer</li>
                        <li> Fully equipped kitchen with dishwasher wall oven cook top and microwave</li>
                        <li>Lounge and dinning room with TV DVD and VCR balcony</li>
                        <li>Ceiling fans throughout</li>
                        <li><strong>New</strong> - double &quot;Murphy&quot; bed,&nbsp;full size double innerspring
                            mattress (pull down from a wall cupboard) installed
                        </li>
                        <?php break;
                    case 2: ?>
                        <li>Unit 5 3 bedroom townhouse</li>
                        <li>Master bedroom with two-way bathroom queen bed TV bedroom airconditioned</li>
                        <li>2nd bedroom 2x single beds</li>
                        <li>3rd bedroom 2x single beds</li>
                        <li>Fully equipped kitchen with oven cooktop dishwasher and microwave</li>
                        <li>Bathroom laundry with washer dryer</li>
                        <?php break;
                    case 3: ?>
                        <li>Unit 91 three bedroom unit</li>
                        <li>Master bedroom with ensuite queen bed and TV DVD and balcony</li>
                        <li>2nd bedroom 3 x single beds & balcony</li>
                        <li>2nd bathroom</li>
                        <li>3rd bedroom 1x double bed & Balcony</li>
                        <li>Laundry with washer dryer</li>
                        <li>Fully equipped kitchen with wall oven cook top microwave and dishwasher & Balcony</li>
                        <li>Lounge and dinning room with TV DVD and balcony</li>
                        <?php break;
                    case 4: ?>
                        <li>Unit 18 two bedroom unit</li>
                        <li>Master bedroom ensuite queen bed and TV</li>
                        <li>2nd Bedroom 2x single beds</li>
                        <li>Second bathroom / laundry with bath and washer dryer</li>
                        <li>Fully equipped kitchen with wall oven, cook top, microwave and dishwasher</li>
                        <li>Lounge and dinning room with TV, DVD, VCR, and balcony</li>
                        <?php break;
                    case 5: ?>
                        <li>Unit 69 3 bedroom unit</li>
                        <li>Main bedroom with ensuite and queen bed</li>
                        <li>2nd bedroom 2x single beds</li>
                        <li>3rd bedroom 2x single beds balcony</li>
                        <li>Separate WC</li>
                        <li>Bathroom / laundry with washer dryer</li>
                        <li>Fully equipped kitchen with wall oven cooktop dishwasher and microwave</li>
                        <li>Lounge dinning room with TV DVD VCR balcony</li>
                    <?php } ?>
            </ul>
            <p><img src="include/view/images/book_now.jpg" alt="Book Now" width="71" height="17" hspace="0" vspace="0"
                    onClick="window.location.href='make_booking.php?id=<?php echo $booking['unit_id'] ?>'"></p>
            <h2>Unit Availability</h2>
            <?php include "calendar.php"; ?>
            <p><span class="Green"><strong>Pending</strong></span> - booking to be confirmed<br>
                <span class="Blue">Booked</span></p>
            <p>All units are a minimum 3 night stay.</p>
        </div>
    </div>

<?php
AddFooter_CloseMain();
?>

<script language="javascript" src="include/view/lightbox.js"></script>
</html>