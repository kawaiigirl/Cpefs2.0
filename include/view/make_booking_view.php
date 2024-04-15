<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<div class="row"><h1 class="header">Make a Booking</h1>
    <?php
    if ($success != "")
    {
        echo '<div class="card">' . $success . '</div>';
    }
    else
    {
    ?>
    <div class="midColumn">
        <div class="card">
            <form name="form2" method="post" action="">
                <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">

                        <tr valign="middle">
                            <td width="12%" height="25">&nbsp;</td>
                            <td width="20%">Unit</td>
                            <td width="78%" height="25">
                                <label>
                                    <select name="unit_id" id="unit_id" style="height:20px;">
                                        <?php getUnitsSelectOptions(setFromPostOrGet('unit_id'));?>
                                    </select>
                                    <span class='error'><?php if(isset($errors['unit_id'])) echo $errors['unit_id'];?></span>
                                </label></td>
                        </tr>
                        <tr valign="middle">
                            <td width="12%" height="25">&nbsp;</td>
                            <td width="20%" height="25">First Name</td>
                            <td width="68%" height="25">
                                <label>
                                    <input type="text" name="firstname" id="firstname" value="<?php echo $_POST['firstname']?>">&nbsp;<span class='error'><?php if(isset($errors['firstname'])) echo $errors['firstname'];?></span>
                                </label></td>
                        </tr>
                        <tr valign="middle">
                            <td width="12%" height="25">&nbsp;</td>
                            <td width="20%" height="25">Last Name</td>
                            <td width="68%" height="25">
                                <label>
                                    <input type="text" name="lastname" id="lastname" value="<?php echo $_POST['lastname']?>">&nbsp;<span class='error'><?php if(isset($errors['lastname'])) echo $errors['lastname'];?></span>
                                </label></td>
                        </tr>
                        <tr valign="middle">
                            <td height="25">&nbsp;</td>
                            <td height="25">Email</td>
                            <td height="25">
                                <label>
                                    <input type="text" name="email" id="email" value="<?php echo $_POST['email']?>">&nbsp;<span class='error'><?php if(isset($errors['email'])) echo $errors['email'];?></span>
                                </label></td>
                        </tr>
                        <tr valign="middle">
                            <td height="25">&nbsp;</td>
                            <td height="25">Telephone</td>
                            <td height="25">
                                <label>
                                    <input type="text" name="phone" id="phone" value="<?php echo $_POST['phone']?>">&nbsp;<span class='error'><?php if(isset($errors['phone'])) echo $errors['phone'];?></span>
                                </label></td>
                        </tr>
                        <tr valign="middle">
                            <td height="25">&nbsp;</td>
                            <td height="25">Number Of Nights</td>
                            <td height="25">
                                <select name="nights" id="nights" style="height:20px;">
                                    <option <?php if(IsPostSetAndEquals("nights",""))echo "Selected";?> value="">Please select</option>
                                    <option <?php if(IsPostSetAndEquals('nights',"3"))echo "Selected";?> value="3">3 Nights (Fri-Mon)</option>
                                    <option <?php if(IsPostSetAndEquals("nights","4"))echo "Selected";?> value="4">4 Nights (Mon-Fri)</option>
                                    <option <?php if(IsPostSetAndEquals("nights","7"))echo "Selected";?> value="7">7 Nights (Mon-Mon)</option>
                                    <option <?php if(IsPostSetAndEquals("nights","14"))echo "Selected";?> value="14">14 Nights (Mon-Mon)</option>
                                </select>
                                <span class='error'><?php if(isset($errors['nights'])) echo $errors['nights'];?></span>
                            </td>
                        </tr>
                        <tr valign="middle">
                            <td height="25">&nbsp;</td>
                            <td height="25">Check In Date</td>
                            <td height="25">
                                <input type="text" name="check_in_date" id='check_in_date' readonly value="<?php if(isset($_POST['check_in_date'])) echo $_POST['check_in_date'];?>" style="width:84px;">
                                <a href="javascript:showCal('Calendar1')"><img src="images/calendar.png" align="absmiddle" alt="Select Check-in-date" border="0"></a>&nbsp;<span class='error'><?php if(isset($errors['check_in_date'])) echo $errors['check_in_date'];?></span>
                            </td>
                        </tr>
                        <tr valign="middle">
                            <td height="25">&nbsp;</td>
                            <td height="25" colspan="2">
                                <input type="checkbox" name="agree" id='agree' value="yes"> I agree to the
                                <a href="legals.php">Legal Terms</a>&nbsp;<span class='error'><?php if(isset($errors['agree'])) echo $errors['agree'];?></span>
                            </td>
                        </tr>
                        <tr>
                            <td height="25" colspan="2" valign="top">&nbsp;</td>
                            <td height="25" valign="top"><label>
                                    <input type="submit" name="button" id="button" value="Submit">
                                </label> <label>
                                    <input type="reset" name="button2" id="button2" value="Clear">
                                </label></td>
                        </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php
}

AddFooter_CloseMain();
?>
</html>
