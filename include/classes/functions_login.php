<?php

/**
 * @param $email
 * @param $type
 * @param null $id
 * @return false|mixed returns false if no row found. else returns the member/admin associated with the email
 */
function validateEmail($email, $type, $id = null): mixed
{
    global $dbc;
    $clean_email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if($email == $clean_email && filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $parameters = ['email' => $email];
        if($type == "admin")
        {
            $sql = "Select * from cpefs_admin where email =:email";
            if($id != null)
            {
                $sql .= " And id != :id";
                $parameters['id'] = $id;
            }
        }
        else
        {
            $sql = "Select * from cpefs_members where member_email =:email";
            if($id != null)
            {
                $sql .= " And member_id != :member_id";
                $parameters['member_id'] = $id;
            }
        }
        return $dbc->getSingleRowPDO($sql, __LINE__, __FILE__, $parameters);
    }
    else
    {
        return false;
    }
}
function verifyMemberPasswordFromID($member_id, $password): bool
{
    global $dbc;
    $qry = "Select * from cpefs_members where member_id=:member_id";
    $row = $dbc->getSingleRowPDO($qry, __LINE__, __FILE__, array("member_id" => $member_id));

    return password_verify($password, $row['member_password']);
}
function validateNewPassword($newPassword, $confirmPassword): array
{
    $validator = new Validator();
    $returnArray = array();
    $returnArray['errors'] = false;
    if(!$validator->General($newPassword))
    {
        $returnArray['newPassword'] = "<span class='error'> [Required]</span>";
        $returnArray['errors'] = true;
    }
    if($newPassword != $confirmPassword)
    {
        $returnArray['confirmPassword'] = "<span class='error'> [Passwords Don't Match]</span>";
        $returnArray['errors'] = true;
    }
    return $returnArray;
}
function validateUpdatedPassword($newPassword, $confirmPassword, $oldPassword, $member_id): array
{
    $validator = new Validator();

    $returnArray = validateNewPassword($newPassword, $confirmPassword);

    if(!$validator->General($oldPassword))
    {
        $returnArray['password'] = "<span class='error'> [Required]</span>";
        $returnArray['errors'] = true;
    }
    elseif(!verifyMemberPasswordFromID($member_id, $oldPassword))
    {
        $returnArray['password'] = "<span class='error'> [Password Incorrect]</span>";
        $returnArray['errors'] = true;
    }
    return $returnArray;
}
function registerAdmin($name, $is_admin, $email, $password): bool
{
    global $dbc;
    $test = $dbc->doesThisExistPDO("Select * from cpefs_admin WHERE email = :email", __LINE__, __FILE__, array("email" => $email));
    echo $test;
    if($test)
    {
        return false;
    }
    else
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $dbc->insert("Insert into cpefs_admin set name = ?, email=?, password = ?,is_admin = ?", __LINE__, __FILE__, array("sssi", &$name, &$email, &$hash, &$is_admin));
    }
}
function updateAdmin($id, $name, $is_admin, $email): bool
{
    global $dbc;
    return $dbc->update("UPDATE cpefs_admin set name = ?, email=?,is_admin = ? WHERE id = ?", __LINE__, __FILE__, array("sssi", &$name, &$email, &$is_admin, &$id));
}
function validateAdmin($name, $email, $password = "", $password2 = "", $id = null): array
{
    $validator = new Validator();
    $returnArray = array();
    $returnArray['errors'] = 0;
    $returnArray['pwd_errors'] = 0;
    if(!$validator->General($name))
    {
        $returnArray['name'] = "<span class='error'> [Required]</span>";
        $returnArray['errors'] = 1;
    }
    elseif(!$validator->Email($email))
    {
        $returnArray['email'] = "<span class='error'> [Invalid Email Address]</span>";
        $returnArray['errors'] = 1;
    }
    elseif(validateEmail($email, "member", $id))
    {
        $returnArray['email'] = "<span class='error'> [This Email Address already exists]</span>";
        $returnArray['errors'] = 1;
    }
    if(!$validator->General($password))
    {
        $returnArray['password'] = "<span class='error'> [Required]</span>";
        $returnArray['pwd_errors'] = 1;
    }
    if(!$validator->General($password2))
    {
        $returnArray['password2'] = "<span class='error'> [Required]</span>";
        $returnArray['pwd_errors'] = 1;
    }
    if($password != $password2)
    {
        $returnArray['password2'] = "<span class='error'> [Passwords Don't Match]</span>";
        $returnArray['pwd_errors'] = 1;
    }
    return $returnArray;
}
function validateMember($first_name, $last_name, $address, $suburb, $postcode, $phone, $email, $id = null): array
{
    $validator = new Validator();
    $returnArray = array();
    $returnArray['errors'] = 0;
    $returnArray['pwd_errors'] = 0;
    if(!$validator->General($first_name))
    {
        $returnArray['firstname'] = "<span class='error'> [Required]</span>";
        $returnArray['errors'] = 1;
    }
    if(!$validator->General($last_name))
    {
        $returnArray['lastname'] = "<span class='error'> [Required]</span>";
        $returnArray['errors'] = 1;
    }
    if(!$validator->General($address))
    {
        $returnArray['address'] = "<span class='error'> [Required]</span>";
        $returnArray['errors'] = 1;
    }
    if(!$validator->General($suburb))
    {
        $returnArray['suburb'] = "<span class='error'> [Required]</span>";
        $returnArray['errors'] = 1;
    }
    if(!$validator->General($postcode))
    {
        $returnArray['postcode'] = "<span class='error'> [Required]</span>";
        $returnArray['errors'] = 1;
    }
    elseif(!$validator->Number($postcode))
    {
        $returnArray['postcode'] = "<span class='error'> [Post Code must be a number]</span>";
        $returnArray['errors'] = 1;
    }
    elseif($postcode < "4000" || $postcode > "4999")
    {
        $returnArray['postcode'] = "<span class='error'> [Post Code must to start with 4]</span>";
        $returnArray['errors'] = 1;
    }
    if(!$validator->General($phone))
    {
        $returnArray['phone'] = "<span class='error'> [Required]</span>";
        $returnArray['errors'] = 1;
    }
    if(!$validator->General($email))
    {
        $returnArray['email'] = "<span class='error'> [Required]</span>";
        $returnArray['errors'] = 1;
    }
    elseif(!$validator->Email($email))
    {
        $returnArray['email'] = "<span class='error'> [Invalid Email Address]</span>";
        $returnArray['errors'] = 1;
    }
    elseif(validateEmail($email, "member", $id))
    {
        $returnArray['email'] = "<span class='error'> [This Email Address already exists]</span>";
        $returnArray['errors'] = 1;
    }
    return $returnArray;
}
function registerMember($name, $first_name, $last_name, $address, $suburb, $postcode, $phone, $email, $password): bool
{
    global $dbc;
    $numRows = $dbc->getNumRows("Select * from cpefs_members WHERE member_email = ?", __LINE__, __FILE__, array("s", &$email));
    if($numRows > 0)
    {
        return false;
    }
    else
    {
        $qry = "member_name = ?";
        $qry .= ",member_firstname=?";
        $qry .= ",member_lastname=?";
        $qry .= ",member_address=?";
        $qry .= ",member_suburb=?";
        $qry .= ",member_postcode=?";
        $qry .= ",member_telephone=?";
        $qry .= ",member_email=?";
        $qry .= ",member_password=?";

        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $dbc->insert("Insert into cpefs_members set $qry", __LINE__, __FILE__, array("sssssisss", &$name, &$first_name, &$last_name, &$address, &$suburb, &$postcode, &$phone, &$email, &$hash));
    }
}
function sendAdminRegistrationEmail($name): void
{

    $body = "Hi Administrator,<br><br>A new member,$name, has registered. Please go to the admin section to see their 
					details.<br><br>Regards,<br>CPEFS";
    $altBody = "Hi Administrator, 
                A new member, $name, has registered. Please go to the admin section to see their 
                details.  
                Regards, 
                CPEFS";
    sendEmail(0, get_admin_receive_email_id(), "CPEFS - New Member Registration", $body, $altBody, "Registration", "Registration", "Admin");
}
function updateMember($id, $name, $first_name, $last_name, $address, $suburb, $postcode, $phone, $email): bool
{
    global $dbc;
    $qry = "member_name = ?";
    $qry .= ",member_firstname=?";
    $qry .= ",member_lastname=?";
    $qry .= ",member_address=?";
    $qry .= ",member_suburb=?";
    $qry .= ",member_postcode=?";
    $qry .= ",member_telephone=?";
    $qry .= ",member_email=?";

    return $dbc->update("UPDATE cpefs_members set $qry WHERE member_id = ?", __LINE__, __FILE__, array("sssssisss", &$name, &$first_name, &$last_name, &$address, &$suburb, &$postcode, &$phone, &$email, &$id));
}
function validateMemberLogin($email, $password): array
{
    $validator = new Validator();
    $returnArray = array();
    $returnArray['error'] = false;
    if(!$validator->General($email))
    {
        $returnArray['email'] = "<span style='color:red'>[Required]</span>";
        $returnArray['error'] = true;
    }
    elseif(!$validator->Email($email))
    {
        $returnArray['email'] = "<span style='color:red'>[Invalid]</span>";
        $returnArray['error'] = true;
    }
    if(!$validator->General($password))
    {
        $returnArray['password'] = "<span style='color:red'>[Required]</span>";
        $returnArray['error'] = true;
    }
    return $returnArray;
}
function verifyMemberLogin($email, $password): array
{
    global $dbc;
    $qry = "Select * from cpefs_members where member_email=?";
    $row = $dbc->getSingleRow($qry, __LINE__, __FILE__, array('s',&$email));

    $returnArray = array();
    $returnArray['password_expired'] = false;

    if($row && password_verify($password, $row['member_password']))
    {
        if($row['password_expired'])
        {
            sendPwdRecoveryEmail($row['member_email'], $row['member_name'], "functions_login", "member");
            $returnArray['success'] = true;
            $returnArray['password_expired'] = true;
            return $returnArray;
        }
        else
        {
            $returnArray['success'] = true;
        }
    }
    else
    {
        $returnArray['success'] = false;
    }
    return $returnArray;
}
function verifyAdminLogin($email, $password): array
{
    global $dbc;
    $qry = "Select * from cpefs_admin where email=:email";
    $row = $dbc->getSingleRowPDO($qry, __LINE__, __FILE__, array("email" => $email));

    $returnArray = array();
    $returnArray['password_expired'] = false;

    if(password_verify($password, $row['password']))
    {
        if($row['password_expired'])
        {
            sendPwdRecoveryEmail($row['email'], $row['name'], "functions_login", "admin member");
            $returnArray['password_expired'] = true;
        }
        else
        {
            if($row['is_admin'] != 1 && $row['is_admin'] != 0)
            {
                $_SESSION['manager'] = $row['id'];
            }
            else
            {
                $_SESSION['admin'] = $row['id'];
            }
            $_SESSION['IS_ADMIN'] = $row['is_admin'];
        }
        $returnArray['success'] = true;
    }
    else
    {
        $returnArray['success'] = false;
    }
    return $returnArray;
}
function updateAdminPassword($password, $email): void
{
    global $dbc;
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    $dbc->update("UPDATE cpefs_admin SET password=?, password_expired = 0 WHERE email=?", __LINE__, __FILE__, array("ss", &$pass_hash, &$email));
    $dbc->delete("DELETE FROM cpefs_pwd_reset WHERE email=?", __LINE__, __FILE__, array("s", &$email));
}

function updateMemberPasswordFromID($password, $id): void
{
    global $dbc;
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    $dbc->updatePDO("UPDATE cpefs_members SET member_password=:password WHERE member_id=:id", __LINE__, __FILE__, array("id" => $id, 'password' => $pass_hash));
}

function updateMemberPasswordFromEmail($password, $email): void
{
    global $dbc;
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    $dbc->update("UPDATE cpefs_members SET member_password=?, password_expired = 0 WHERE member_email=?", __LINE__, __FILE__, array("ss", &$pass_hash, &$email));
    $dbc->delete("DELETE FROM cpefs_pwd_reset WHERE email=?", __LINE__, __FILE__, array("s", &$email));
}

function sendPwdRecoveryEmail($email, $name, $page, $recipient): string
{
    global $dbc;
    $expFormat = mktime(
        date("H"), date("i"), date("s"), date("m"), (int)date("d") + 1, date("Y")
    );
    $expDate = date("Y-m-d H:i:s", $expFormat);
    $token = md5(microtime() . $email);
    $addToken = substr(md5(uniqid(rand(), 1)), 3, 10);
    $token = $token . $addToken;
    $dbc->insert("INSERT INTO cpefs_pwd_reset (email, token, expDate) VALUES (?, ?, ?);", __LINE__, __FILE__, array("sss", &$email, &$token, &$expDate));

    $relative_url = '';
    if($recipient == "admin member")
    {
        $relative_url = '/admin';
    }

    //URLEncode GET parameters
    $email = urlencode($email);
    $token = urlencode($token);

    $reset_Password_link = SITE_URL . $relative_url . "/reset_password.php?token=$token&email=$email&action=reset";

    $body = "<p>Dear $name,</p>";
    $body .= "<p>Please click on the following link to reset your password.</p>";
    $body .= "<p>-------------------------------------------------------------</p>";
    $body .= "<p><a href='$reset_Password_link' target='_blank'>$reset_Password_link</a></p>";
    $body .= "<p>-------------------------------------------------------------</p>";
    $body .= "<p>Please be sure to copy the entire link into your browser. The link will expire after 1 day for security reasons.</p>";
    $body .= '<p>If you did not request this forgotten password email, no action is needed, your password will not be reset.</p>';
    $body .= "<p>Thanks,<br>";
    $body .= "Cpefs Admin</p>";
    $altBody = "Please view this email in html";
    $subject = "Password Recovery - CPEFS";

    return sendEmail("0", urldecode($email), $subject, $body, $altBody, $page, "password recovery", $recipient);
}
function GetMember($email)
{
global $dbc;
$qry = "Select * from cpefs_members where binary member_email=?";
return $dbc->getSingleRow($qry, __LINE__, __FILE__, array("s", &$email));
}