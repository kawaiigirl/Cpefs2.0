<?php
$token = urldecode($_GET["token"]);
$email = urldecode($_GET["email"]);

$curDate = date("Y-m-d H:i:s");
$expDate=0;
$error="";
global $dbc;
if(TYPE =="admin"){
    $forgotPwdURL = SITE_URL."/admin/forgot-password.php";
    $loginURL = SITE_URL."/admin/";
}
else
{
    $loginURL = SITE_URL;
    $forgotPwdURL = SITE_URL."/forgot-password.php";
}
$res = $dbc->getSingleRow("SELECT * FROM cpefs_pwd_reset WHERE token=? and email=?",__LINE__,__FILE__,array("ss",&$token,&$email));
if($res)
    $expDate = $res['expDate'];
if (!$res || $curDate > $expDate)
{
    $error .= "<h3>Invalid Link</h3>
                    <p>The link is invalid/expired. Either you did not copy the correct link
                    from the email, or you have already used the key in which case it is 
                    deactivated.</p>
                    <p><a href='$forgotPwdURL'>
                    Click here</a> to reset password.</p>";

    echo "<div class='error'>".$error."</div><br />";

}
else
{
    if(isset($_GET["action"]) && ($_GET["action"]=="reset") && !isset($_POST["action"]))
    {
        ?>
        <br />
        <form method="post" action="" name="update">
            <input type="hidden" name="action" value="update" />
            <br /><br />
            <label><strong>Enter New Password:</strong></label><br />
            <input type="password" name="pass1" maxlength="15" required />
            <br /><br />
            <label><strong>Re-Enter New Password:</strong></label><br />
            <input type="password" name="pass2" maxlength="15" required/>
            <br /><br />
            <input type="hidden" name="email" value="<?php echo $email;?>"/>
            <input type="submit" value="Reset Password" />
        </form>
        <?php
    }
    elseif(isset($_POST["email"]) && isset($_POST["action"]) && ($_POST["action"]=="update"))
    {
        $error = "";
        $pass1 = $_POST["pass1"];
        $pass2 =  $_POST["pass2"];
        $email = $_POST["email"];
        $curDate = date("Y-m-d H:i:s");
        if ($pass1 != $pass2) {
            $error .= "<p>Password do not match, both passwords should be the same.<br /><br /></p>";
        }
        if ($error != "") {
            echo "<div class='error'>" . $error . "</div><br />";
        } else {
            if(TYPE == "admin") {
                updateAdminPassword($pass1,$email);

            }elseif(TYPE == "member")
            {
                updateMemberPasswordFromEmail($pass1, $email);
            }

            echo "<div class='error'><p>Congratulations! Your password has been updated successfully.</p>
        <p><a href='$loginURL'>
        Click here</a> to Login.</p></div><br />";
        }
    }
}