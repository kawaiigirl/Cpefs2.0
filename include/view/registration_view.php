<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<div class="row">
    <div class="singleColumn"><h1 class="header">Member Registration</h1>
        <div class="card clearfix">
            <?php
            if($success=="")
            {
                ?>
                <form name="form1" method="post" action="">
                    <div class="row clearfix">
                    <?php
                    GetMemberFormHtml($errors);
                    ?>
                    </div>
                    <div class="row">
                    <div class="leftColumn leftInner">
                        <div class="row">
                            <label for="member_password">Password</label><input type="text" name="member_password" id="member_password" value="<?=SetFromPost('member_password') ?>">
                            <?= SetFromArray($errors,'password') ?></div>
                    </div>
                    <div class="rightColumn rightInner">
                        <div class="row">
                            <label for="member_password1">Confirm Password</label><input type="text" name="member_password1" id="member_password1" value="<?=SetFromPost('member_password1') ?>">
                            <?= SetFromArray($errors,'password2') ?></div>
</div>
                    </div>
                    <div class="g-recaptcha" data-sitekey="<?php echo SITE_KEY; ?>"></div>
                    <script type="text/javascript"
                        src="https://www.google.com/recaptcha/api.js?hl=en_GB">
                    </script>
                    <?php echo $msgRecaptcha?>
                    <input type="submit" name="Submit" id="Submit" value="Submit">
            </form>
            <?php
        }
        else
        {
            echo $success;
        }
        ?>
        </div>
    </div>
</div>

    <?php
    AddFooter_CloseMain();
    ?>
</html>