<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<div class="row">
    <h1 class="header">Reset Password</h1>
    <div class="card">
    <?php
    include "include/reset_password_body.php";
    ?>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>