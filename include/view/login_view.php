<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<div class="row">
    <div class="midColumn smallMidColumn"><h1 class="header">Login</h1>
        <div class="card clearfix">
            <form method="post">
                <div class="row"><label for="email">Email</label>
                    <input type="text" name="email" id="email" value="<?php DisplayPost('email');?>">&nbsp;<?=$eMsgEmail ?></div>
                <div class="row"><label for="password">Password</label>
                    <input type="password" name="password" id="password" value="<?php DisplayPost('password');?>"><?=$eMsgPassword ?></div>
                <?php
                if (isset($_SESSION['login_attempts']))
                {
                    ?>
                    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=en_GB"></script>
                    <div class="g-recaptcha" data-size='compact' data-sitekey="<?= SITE_KEY; ?>"></div>
                    <?=$msgRecaptcha ?>
                    <?php
                }
                ?>
                        <input type="submit" name="submit" id="submit" value="Submit">&nbsp;&nbsp;<?=$errMsg ?>
            </form>
            <a href="forgot_password.php" class="Grey">Forgot password</a> |
            <a href="registration.php" class="Grey">Join</a>
        </div>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>
