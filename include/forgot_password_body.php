<?php
if(isset($_POST['email']) && $_POST['email']!= null)
{
    $member = validateEmail($_POST['email'],TYPE);
    if($member)
    {
        if(TYPE == "admin")
            $res= sendPwdRecoveryEmail($_POST['email'], $member['name'],"admin/forgot-password.php", "admin member");
        else
            $res= sendPwdRecoveryEmail($_POST['email'], $member['member_name'],"forgot-password.php", "member");
        if(LOCALHOST)
            echo $res;
    }
}
if(isset($_POST['email']))
{
    echo "<br>Please check your email for a password reset link<br> <br><span style='font-size: smaller'>Contact <a href='mailto: admin@cpefs.com.au'>admin@cpefs.com.au</a> if you have any issues</span>";
}
else
{
?>
    <form method="post" action="" name="reset"><br />
    <label><strong>Enter Your Email Address:</strong></label><br /><br />
    <input type="email" name="email" placeholder="username@email.com" />
    <br /><br />
    <input type="submit" value="Reset Password"/>

    <br /><br />
    </form>
<?php
}
?>