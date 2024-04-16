<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<div class="row">
    <?php
    if ($success != "")
    {
        echo '<div class="card">' . $success . '</div>';
    }
    else
    {
    ?>
    <div class="midColumn"><h1 class="header">Make Booking</h1>
        <div class="card clearfix">

            <form name="form2" method="post" action="">
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
                        <option <?php if (IsPostSetAndEquals("nights", "14")) echo "Selected"; ?> value="14" selected>14 Nights (Mon-Mon) </option>
                    </select> <span class='error'><?php if (isset($errors['nights'])) echo $errors['nights']; ?></span>
                </div>
                <div class="row"><label for="check_in_date">Check In Date</label>
                    <input type="text" name="check_in_date" id='check_in_date' onclick="showCal('Calendar1')" readonly value="<?php if (isset($_POST['check_in_date'])) echo $_POST['check_in_date']; ?>" >
                    <a href="javascript:showCal('Calendar1')"><img src="images/calendar.png" align="absmiddle" alt="Select Check-in-date" border="0"></a>&nbsp;<span class='error'><?php if (isset($errors['check_in_date'])) echo $errors['check_in_date']; ?></span>
                </div>
                <input type="checkbox" name="agree" id='agree' value="yes"> <label for="agree">I agree to the
                    <a href="legals.php">Legal Terms </a></label><span class='error'><?php if (isset($errors['agree'])) echo $errors['agree']; ?></span>
                <input type="submit" name="button" id="button" value="Submit">
                <input type="reset" name="button2" id="button2" value="Clear">
            </form>
        </div>
    </div>
</div>
<?php
}
AddFooter_CloseMain();
?>
<script language="javascript" src="include/scripts/cal2.js">
    /*
    Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
    Script featured on/available at http://www.dynamicdrive.com/
    This notice must stay intact for use
    */
</script>
<script language="javascript" src="include/scripts/cal_conf2.js"></script>

</html>
