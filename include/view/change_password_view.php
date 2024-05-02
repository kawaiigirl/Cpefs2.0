<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<div class="row">
    <h1 class="header">Change Password</h1>
    <div class="card">
        <?php
        if($success!="")
            echo $success;
        else
        {
        ?>
            <form name="form1" method="post" action="">
                <div class="row">
                    <label for='oldPassword'>Old Password</label>
                    <input type="password" name="oldPassword" id="oldPassword">
                </div>
                <?php echo $msgOldPassword ?>
                <div class="row">
                    <label for='newPassword'>New Password</label>
                    <input type="password" name="newPassword" id="newPassword" value='<?php if (isset($_POST['newPassword'])) echo $_POST['newPassword']; ?>'>
                </div>
                <?php echo $msgNewPassword ?>
                <div class="row">
                    <label for='confirmPassword'>Verify password </label>
                    <input type="password" name="confirmPassword" id="confirmPassword" value='<?php if (isset($_POST['confirmPassword'])) echo $_POST['confirmPassword']; ?>'>
                </div>
                <?php echo $msgConfirmPassword ?>
                <div class="row"><input type="submit" name="Submit" id="Submit" value="Submit"></div>
            </form>
    <?php
    }
    ?>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>