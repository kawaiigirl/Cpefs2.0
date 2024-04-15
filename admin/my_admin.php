<?php
include "include/admin_header.php";
global $database;
$errors = "";
$f=0;
	$booking=getSingleData("Select * from $database[admin] ");
	$_POST['login']=$booking['login'];
	$_POST['pwd']=base64_decode($booking['password']);


?>
<table width="98%" height="100%" cellpadding="0"  cellspacing="0">
  <tr>
    <td colspan="3"><table width="100%"  cellspacing="0" cellpadding="0">
        <tr>
          <td class="body_head1">&nbsp;</td>
          <td align="center" class="body_bg1">Manage Admin </td>
          <td class="body_head2">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#FFA3AB"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    <td height="360" width="100%" align="center" valign="middle">
<table width="350"  cellspacing="0" cellpadding="0" border="0" align="center">
  <tr>
    <td class="error"> 
      <?
		  if(!$errors=="")
			{
				  foreach($errors as $error)
				  {
					echo "<li>$error</li>";
				  }
			}
	  ?>
      <br>
      <br>
    </td>
   </tr>
  </table>
<table width="350" border="1" cellpadding="0" cellspacing="0" bordercolor="#FF75BA" style="border-collapse :collapse" align="center">
  <form name="frm" action="" method="post">
		  <tr>
			  <td class="heading" height="30">Manage Admin Detail</td>
		  </tr>
		  <tr>
			<td align="left" valign="top"><table width="100%" cellpadding="0"  cellspacing="0" bordercolor="#FF6FB7">
              <tr bgcolor="#FFF2F9"> 
                <td height="30" align="right" valign="middle" bgcolor="#FFF2F9" class="label_name1"><font color="#FF0000">*</font>User 
                  Name :</td>
                <td align="left" valign="middle"> <input name="login" type="text" class="inptbox" value="<?php echo $_POST['login']?>"></td>
              </tr>
              <tr> 
                <td align="right" height="30" valign="middle" class="label_name1"><font color="#FF0000">*</font>Password 
                  : </td>
                <td align="left" valign="middle"><input name="pwd" type="text" class="inptbox"  value="<?php echo $_POST['pwd']?>"></td>
              </tr>
            </table></td>
		</tr>
	</form>
</table><?php include "include/admin_footer.php"; ?>