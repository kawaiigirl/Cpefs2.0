<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead(""," <link rel='stylesheet' href='include/view/datepicker.css'>");

AddHeader_StartMain(GetNavLinks());
?>
<form name="form2" method="post" action="">
    <div class="row">
    <?php
    if ($success != "")
    {
        echo '<div class="card">' . $success . '</div>';
    }
    else
    {
    ?>
    <h1 class="header">Make Booking</h1>

        <div class="leftColumn">
            <div class="card clearfix">
                <div class="row"><label for="unit_id"> Unit</label>
                    <select name="unit_id" id="unit_id">
                        <?php getUnitsSelectOptions(setFromPostOrGet('unit_id')); ?>
                    </select>
                    <span class='error'><?php if (isset($errors['unit_id'])) echo $errors['unit_id']; ?></span>
                </div>
                <div class="row"><label for="firstname">First Name </label>
                    <input type="text" name="firstname" id="firstname" value="<?php echo $_POST['firstname'] ?>">&nbsp;<span class='error'><?php if (isset($errors['firstname'])) echo $errors['firstname']; ?></span>
                </div>
                <div class="row"><label for="lastname">Last Name</label>
                    <input type="text" name="lastname" id="lastname" value="<?php echo $_POST['lastname'] ?>">&nbsp;<span class='error'><?php if (isset($errors['lastname'])) echo $errors['lastname']; ?></span>
                </div>
                <div class="row"><label for="email">Email</label>
                    <input type="text" name="email" id="email" value="<?php echo $_POST['email'] ?>">&nbsp;<span class='error'><?php if (isset($errors['email'])) echo $errors['email']; ?></span>
                </div>
                <div class="row"><label for="phone">Telephone</label>
                    <input type="text" name="phone" id="phone" value="<?php echo $_POST['phone'] ?>">&nbsp;<span class='error'><?php if (isset($errors['phone'])) echo $errors['phone']; ?></span>
                </div>
                <div class="row"><label for="nights">Number of Nights</label> <select name="nights" id="nights">
                        <option <?php if (IsPostSetAndEquals("nights", "")) echo "Selected"; ?> value="">Please select </option>
                        <option <?php if (IsPostSetAndEquals('nights', "3")) echo "Selected"; ?> value="3">3 Nights (Fri-Mon) </option>
                        <option <?php if (IsPostSetAndEquals("nights", "4")) echo "Selected"; ?> value="4">4 Nights (Mon-Fri) </option>
                        <option <?php if (IsPostSetAndEquals("nights", "7")) echo "Selected"; ?> value="7">7 Nights (Mon-Mon) </option>
                        <option <?php if (IsPostSetAndEquals("nights", "14")) echo "Selected"; ?> value="14">14 Nights (Mon-Mon) </option>
                    </select> <span class='error'><?php if (isset($errors['nights'])) echo $errors['nights']; ?></span>
                </div>
            </div>
        </div>
        <div class="rightColumn">
            <div class="card">
                <div class="row">

                    <div class="calendar" id="calendar"></div>
                    <input type="hidden" id="check_in_date" name="check_in_date" placeholder="Select a date" readonly value="<?php DisplayPost('check_in_date')?>">
                </div>
                <div class="row">
                    <div class="leftData">Check-In-Date</div>
                    <div class="nonInteractiveInput" id="check_in_date_div"><?php DisplayPost('check_in_date')?></div>
                    <span class='error'><?php if (isset($errors['check_in_date'])) echo $errors['check_in_date']; ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="singleColumn">
                <div class="card clearfix" style="margin-top: 0">
                    <input type="checkbox" name="agree" id='agree' value="yes"> <label for="agree">I agree to the
                        <a href="legals.php">Legal Terms </a></label><span class='error'><?php if (isset($errors['agree'])) echo $errors['agree']; ?></span>
                    <input type="submit" name="button" id="button" value="Submit">
                </div>
            </div>
    </div>
</form>
<?php
}
AddFooter_CloseMain();
?>

<script language="javascript" src="include/datepicker.js"></script>
<?php

if(IsPostSetAndNotEmpty('check_in_date'))
{
    $checkInDate =  SetFromPost('check_in_date');
    $checkInDateArray = explode('/', SetFromPost('check_in_date'));
    $day = "day". $checkInDateArray[2];
    echo "<script>

        // Initialize calendar
        const today = new Date();
        console.log('$checkInDateArray[0] $checkInDateArray[1]')
         selectedDate = new Date($checkInDateArray[0], $checkInDateArray[1] - 1, $checkInDateArray[2]);
        year = $checkInDateArray[0];
        month = $checkInDateArray[1];
        renderCalendar(year, month);
        selectDate(selectedDate,'$day')
        </script>";
}
else{
    echo "<script language='javascript'>
        // Initialize calendar
        const today = new Date();
        renderCalendar(today.getFullYear(), today.getMonth() + 1);
        </script>";
}
// Output JavaScript code to set focus based on the PHP variable
if ($focusOnError != "") {
    echo "<script>document.getElementById('".$focusOnError."').focus();</script>";
}
?>
</html>
