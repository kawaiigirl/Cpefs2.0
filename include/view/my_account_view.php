<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();
AddHeader_StartMain(GetNavLinks());
?>
<div class="row">  <h1 class="header">My Account</h1>
    <div class="singleColumn">

    <?php
    if ($success != "")
        echo '<div class="card">' . $success . '</div>';
    ?>
        <form name="form1" method="post" action="">
        <div class="card">
            <div class="row">
                <?php
                GetMemberFormHtml($errors);
                ?>
                <div class="rightColumn rightInner"
                    <div class="row"><a href='change_password.php' class="book-now change-password" style="margin-top:8px;margin-bottom:10px;padding:12px;text-decoration: none;color:white"><strong>Change Password</strong></a></div>
                </div>
                <div class="row"><input type="submit" name="Submit" id="Submit" value="Submit"></div>
            </div>
        </form>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>