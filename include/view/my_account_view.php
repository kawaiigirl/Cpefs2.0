<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<div class="row"><h1 class="header">My Account</h1>
    <?php
    if ($success != "")
        echo '<div class="card">' . $success . '</div>';
    ?>
    <div class="midColumn">
    <div class="card">
        <form name="form1" method="post" action="">
            <div class="row"><label for="member_firstname">First Name</label>
                <input type="text" name="member_firstname" id="member_firstname"
                       value="<?= $_POST['member_firstname'] ?>">&nbsp;<?= $msgFirstname ?></div>
            <div class="row"><label for="member_lastname">Last Name</label>
                <input type="text" name="member_lastname" id="member_lastname"
                       value="<?php echo $_POST['member_lastname'] ?>">&nbsp;<?php echo $msgLastname ?></div>
            <div class="row"><label for='member_address'>Address</label><input type="text" name="member_address"
                                                                               id="member_address"
                                                                               value="<?php echo $_POST['member_address'] ?>">
                <?php echo $msgAddress ?></div>
            <div class="row"><label for="member_suburb">Suburb</label>
                <input type="text" name="member_suburb" id="member_suburb"
                       value="<?php echo $_POST['member_suburb'] ?>">
                <?php echo $msgSuburb ?></div>
            <div class="row"><label for="member_postcode">Postcode</label><input type="text" name="member_postcode"
                                                                                 id="member_postcode"
                                                                                 value="<?php echo $_POST['member_postcode'] ?>">
                <?php echo $msgPostcode ?></div>
            <div class="row"><label for="member_telephone">Telephone</label>
                <input type="text" name="member_telephone" id="member_telephone"
                       value="<?php echo $_POST['member_telephone'] ?>">
                <?php echo $msgPhone ?></div>
            <div class="row"><label for="member_email">Email</label><input type="text" name="member_email"
                                                                           id="member_email"
                                                                           value="<?php echo $_POST['member_email'] ?>">
                <?php echo $msgEmail ?></div>
            <div class="row"><a href='change-password.php'>Change Password </a></div>
            <div class="row"><input type="submit" name="Submit" id="Submit" value="Submit"></div>
        </form>
    </div>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>