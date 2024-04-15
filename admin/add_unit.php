<?php
include "include/admin_header.php";
include("redirect_to_adminlogin.php");
require_once "../inc/validator.php";

$f=0;
$validator=new Validator;
$qry_img = "";
global $dbc;
if(isset($_POST['submit']) && $_POST['submit']=="Submit")
{
	if(!$validator->General($_POST['unit_name'],'Please specify Unit Name'))
	{
		$f=1;
	}

	if(!$validator->General($_POST['unit_location'],'Please specify Unit Location'))
	{
		if($f==0)
		{
			$f=2;
		}
	}
	
	if(!$validator->General($_POST['basic_rate'],'Please specify Basic Rate'))
	{
		if($f==0)
		{
			$f=3;
		}
	}
	elseif(!$validator->Number($_POST['basic_rate'],'Basic Rate must be Numeric'))
	{
		if($f==0)
		{
			$f=3;
		}
	}

	if(!$validator->General($_POST['peak_rate'],'Please specify Peak Rate'))
	{
		if($f==0)
		{
			$f=4;
		}
	}
	elseif(!$validator->Number($_POST['peak_rate'],'Peak Rate must be Numeric'))
	{
		if($f==0)
		{
			$f=4;
		}
	}

	if(!$validator->General($_POST['weekend_rate'],'Please specify Weekend Rate'))
	{
		if($f==0)
		{
			$f=5;
		}
	}
	elseif(!$validator->Number($_POST['weekend_rate'],'Weekend Rate must be Numeric'))
	{
		if($f==0)
		{
			$f=5;
		}
	}
	
	if(!$validator->General($_POST['manager_email'],'Please specify Manager&rsquo;s Email'))
	{
		if($f==0)
		{
			$f=6;
		}
	}
	elseif(!$validator->Email($_POST['manager_email'],'Please specify a Valid Email Address'))
	{
		if($f==0)
		{
			$f=6;
		}
	}
	if($_POST["unit_image_name"]=="")
	{
		if($_FILES["unit_image"]['name']=="")
		{
			$validator->addError("Please upload an image for this Unit");
			if($f==0)
			{
				$f=7;
			}
		}
	}
	if(!$validator->foundErrors())
	{
		if($_FILES["unit_image"]['name']!="")	//Large Image
		{
			$file_name=trim($_FILES["unit_image"]["name"]);
			$file_type=trim($_FILES["unit_image"]["type"]);
			$file_size=trim($_FILES["unit_image"]["size"]);	

			$image_types = Array ("image/bmp", "image/jpeg", "image/pjpeg", "image/gif", "image/x-png");

			$uploaddir="../unit_image/";
			$ext = substr(strrchr($file_name, "."), 1); 
			$randName1 = md5(rand() * time());
			$randName=$randName1."-".str_replace(" ","-",$_POST['unit_name']);
			$newname=$randName . "." . $ext;
			$filePath = $uploaddir . $randName . '.' . $ext;
			if(in_array (strtolower ($file_type), $image_types)) 
			{
				move_uploaded_file($_FILES["unit_image"]["tmp_name"],$filePath);
				$qry_img =", unit_image='$newname'";
			}
			else
			{
				$validator->addError("Invalid Unit Image type $file_type");
				if($f==0)
				{
					$f=7;
				}
			}
		}
	}
	if(!$validator->foundErrors())
	{
		$qry = " unit_name=?, unit_location=?, basic_rate=?, peak_rate=?, weekend_rate=?, manager_email=?";
		$qry .= $qry_img;
		
		if($_GET['id']=="")
		{
			$qry .= ", unit_status = 1 ";
			$qry .= ", minimum_stay = 3 ";
			
			$dbc->insert(" Insert into cpefs_units set $qry",__LINE__,__FILE__,array("ssddds",& $_POST["unit_name"],& $_POST["unit_location"],& $_POST["basic_rate"],& $_POST["peak_rate"],& $_POST["weekend_rate"],& $_POST["manager_email"]));
			header("Location: unit_listing.php?opt=1");
        }
		else
		{
			$dbc->update("Update cpefs_units set $qry where unit_id=?",__LINE__,__FILE__,array("ssdddsi",& $_POST["unit_name"],& $_POST["unit_location"],& $_POST["basic_rate"],& $_POST["peak_rate"],& $_POST["weekend_rate"],& $_POST["manager_email"],&$_GET['id']));
            header("Location: unit_listing.php?opt=2");
        }
        exit;
    }
}

if(count($_POST)<=0 && isset($_GET['id']) && $_GET['id']!="")

{
	$row=$dbc->getSingleRow("Select * from cpefs_units where unit_id=$_GET[id]",__LINE__,__FILE__);
	$_POST['manager_email']=show_text($row['manager_email']);
	$_POST['unit_name']=show_text($row['unit_name']);
	$_POST['unit_location']=show_text($row['unit_location']);
	$_POST['basic_rate']=show_text($row['basic_rate']);
	$_POST['peak_rate']=show_text($row['peak_rate']);
	$_POST['weekend_rate']=show_text($row['weekend_rate']);
	$_POST['unit_image_name']=$row['unit_image'];
	$_SESSION['backpage']="unit_listing.php#$_GET[id]";

}

?>
<table width="98%" height="100%" cellpadding="0"  cellspacing="0">
  <tr>
    <td colspan="3"><table width="100%"  cellspacing="0" cellpadding="0">
        <tr>
          <td class="body_head1">&nbsp;</td>
          <td align="center" class="body_bg1">Manage Units</td>
          <td class="body_head2">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#FFA3AB"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    <td height="360" width="100%" align="center" valign="middle">
		<?php
			if($validator->foundErrors())
			{
		?>
		<table width="95%" border="0" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
		  <tr>
		  	<td>
				<?php $validator->listErrors(); ?>
			</td>
		</tr>
		</table>
		<?php
			}
		?>
        <form name="frm" action="" method="post"  enctype="multipart/form-data">
<table width="95%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
		  <tr>
			<td align="left" valign="top"><table width="100%" cellpadding="0"  cellspacing="0" bordercolor="#CCD5E6">
              <tr class="bgc"> 
                <td height="30" align="right" valign="middle" class="label_name1"><span style="color:#FF0000">*</span>Unit
                  Name :</td>
                <td align="left" valign="middle"> <input name="unit_name" size="40" maxlength="100" type="text" class="inptbox" value="<?php if(isset($_POST["unit_name"])) echo $_POST["unit_name"]?>"></td>
              </tr>
              <tr class="bgc1"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style="color:#FF0000">*</span>Location
                  : </td>
                <td align="left" valign="middle"><textarea name="unit_location" rows="4" cols="81"><?php if(isset($_POST['unit_location'])) echo $_POST['unit_location']?></textarea></td>
              </tr>
              <tr class="bgc"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style="color:#FF0000">*</span>Basic Rate
                  : </td>
                <td align="left" valign="middle"><input name="basic_rate"  maxlength="20" type="text" class="inptbox"  value="<?php if(isset($_POST["basic_rate"])) echo $_POST["basic_rate"]?>"></td>
              </tr>
              <tr class="bgc1"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style="color:#FF0000">*</span>Peak Rate
                  : </td>
                <td align="left" valign="middle"><input name="peak_rate" maxlength="20" type="text" class="inptbox"  value="<?php if(isset($_POST["peak_rate"])) echo $_POST["peak_rate"]?>"></td>
              </tr>
              <tr class="bgc"> 
                <td align="right" height="30" valign="middle" class="label_name1"><span style="color:#FF0000">*</span>Weekend Rate
                  : </td>
                <td align="left" valign="middle"><input name="weekend_rate"  maxlength="20" type="text" class="inptbox"  value="<?php if(isset($_POST["weekend_rate"])) echo $_POST["weekend_rate"]?>"></td>
              </tr>
              <tr class="bgc1"> 
                <td height="30" align="right" valign="middle" class="label_name1"><span style="color:#FF0000">*</span>Email Address :</td>
                <td align="left" valign="middle"> <input name="manager_email"  size="40" maxlength="150" type="text" class="inptbox" value="<?php if(isset($_POST["manager_email"])) echo $_POST["manager_email"]?>"></td>
              </tr>
			  <tr> 
                <td height="30" align="right" valign="middle" class="label_name1"><span style="color:#FF0000">*</span>Unit Image :</td>
                <td align="left" valign="middle" > 
					<input name="unit_image" type="file" class="inptbox" size="35">
					<input name="unit_image_name" type="hidden" value="<?php if(isset($_POST["unit_image_name"])) echo $_POST["unit_image_name"]?>">
				</td>
			</tr>
				
				<?php
				if(isset($_GET['id']) && $_GET['id']!="")
				{
					if(isset($_POST['unit_image_name']) && $_POST['unit_image_name']!="")
				?>
			  <tr  class="bgc"> 
                <td align="right" valign="top" class="label_name1" colspan="2" >
					<?php
						echo "<img src='../unit_image/$_POST[unit_image_name]' align='absmiddle'>";
					?>
					
				</td>
              </tr>
			  <?php
				}
				?>
              <tr class="bgc1"> 
                <td height="30" colspan="2" align="center" valign="middle" > 
                <?php
				if($_SESSION['IS_ADMIN']==1)
					echo '<input name="submit" type="submit" class="button1" value="Submit" />';
				?>
				&nbsp;&nbsp; 
					<input name="back" type="button" class="button1" value="Back" onClick="window.location.href='<?php echo $_SESSION['backpage']?>'"></td>
              </tr>
            </table></td>
		</tr>
</table>
</form>
<?php
	switch ($f) 
	{
        case 2:
			set_focus("unit_location");
			break;
		case 3:
			set_focus("basic_rate");
			break;
		case 4:
			set_focus("peak_rate");
			break;
		case 5:
			set_focus("weekend_rate");
			break;
		case 6:
			set_focus("manager_email");
			break;
		case 7:
			set_focus("unit_image");
			break;
		default:
			set_focus("unit_name");
			break;
	}
	include "include/admin_footer.php";
?>