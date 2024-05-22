<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>

<div class="row"><h1 class="header">Contact Us</h1>
    <?php
    if($responseMsg != "")
    {
    ?>
        <div class="midColumn">
            <div class='card clearfix'><?=$responseMsg?></div>
        </div>
    <?php
    }
    ?>
    <div class="leftColumn">
        <div class='card clearfix'>
            <p style="text-align: left">Castlemaine Perkins Employees Friendly Society Ltd<br>
                GPO Box 44<br>
                Brisbane Qld 4001
            </p>
            <p style="text-align: left">
                Phone: <br>0411 259 931<br>
                0421 001 826<br>
            </p>
        </div>
    </div>
    <div class="rightColumn">
        <div class='card clearfix'>
            <form method="post">
                <div class='row'><label class="leftData" for="Name">Name:</label><input name="Name" type="text" id="Name" /></div>
                <div class='row'><label class="leftData" for="Telephone">Telephone:</label><input name="Telephone" type="text" id="Telephone"/></div>
                <div class='row'><label class="leftData" for="Email">Email:</label><input name="Email" type="text" id="Email"/></div>
                <div class='row'><label class="leftData" for="Message">Message:</label><textarea name="Message" id="Message" "></textarea></div>
                <div class='row' style="text-align: center"><div class="g-recaptcha" data-sitekey="<?php echo SITE_KEY; ?>"></div>
                    <script type="text/javascript"
                            src="https://www.google.com/recaptcha/api.js?hl=en-GB">
                    </script></div>
                <div class='row'> <div><input type="submit" name="Submit" value="Send" class="buttons" /></div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>