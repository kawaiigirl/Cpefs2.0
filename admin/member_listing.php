<?php
include "include/admin_header.php";
include_once "redirect_to_adminlogin.php";
include "../inc/functions_login.php";
$_SESSION['backpage']="member_listing.php";
global $dbc;

$ord = $sub = $op = $smn = $sme =$qryParamTypes = $msg = "";
$qryParams = array();

if(isset($_GET['reset_pass'])&&$_GET['reset_pass'] == 'true' && isset($_GET['email'])&&$_GET['email'] != "" )
{
   $member = validateEmail($_GET['email'],"member");
    if($member)
    {
        sendPwdRecoveryEmail($_GET['email'], $member['member_name'], "member_listing.php", "member");
        $msg = "Sent member a reset password email";
    }
}
if(isset($_GET['member_active']))
{
	$active_member = $_GET['member_active'];
}
elseif(isset($_POST['s_member_active']))
{
	$active_member = $_POST['s_member_active'];
}
else
{
	$active_member = 1;
}

if(isset($_POST['submit']))
{
	/* Multiple Deletion Starts Here  */
	if(isset($_POST['delete']) && $_POST['delete'] !="")
	{
		foreach($_POST['delete'] as $del)
		{
			$dbc->delete(" Delete from cpefs_members where member_id = ?",__LINE__,__FILE__,array("i",&$del));
		}
	}
	 /*Multiple Deletion Ends Here */
	if(isset($_POST["id"]) )
	{
        $count = count($_POST["id"]);
        for ($i = 0; $i < $count; $i++) {
            $member_id = $_POST["id"][$i];
            $status = $_POST["status"][$i];
            $payment = $_POST["pay"][$i];
            $current_to = $_POST["current_to"][$i];
            $active = $_POST["active"][$i];
            $dbc->update("Update cpefs_members Set status=?,payment_amt=?,current_to=?, active = ? Where member_id=?",__LINE__,__FILE__,array("issii",&$status,&$payment,&$current_to,&$active,&$member_id));
        }
    }
	$op = 2;
}
elseif(count($_POST)>0 || (isset($_GET['smn']) && $_GET['smn']!="") || (isset($_GET['sme']) && $_GET['sme']!="") || (isset($_GET['status']) && $_GET['status']!=""))
{
	if(count($_POST)>0)
	{
		$_GET['smn']=$_POST['s_member_name'];
		$_GET['sme']=$_POST['s_member_email'];
		$_GET['status']=$_POST['s_member_status'];
		$_GET['member_active'] = $_POST['s_member_active'];
	}

	if(isset($_GET['smn']) && $_GET['smn']!="")
	{
        $smn = $_GET['smn'];
		$sub =" and member_name like :member_name";
        $qryParams['member_name'] ="%".$_GET["smn"]."%";
	}
	if(isset($_GET['sme']) && $_GET['sme']!="")
	{
	    $sme = $_GET['sme'];
		$sub .=" and member_email like :member_email";
        $qryParams['member_email'] = "%".$_GET["sme"]."%";
	}
	if(isset($_GET['status']) && $_GET['status']!="")
	{
		$sub .=" and member_status = :member_status";
        $qryParams['member_status'] = $_GET["status"];
	}
}

$ord_next_name = 1;
$ord_next_email = 3;

if(isset($_GET["ord"]) && $_GET["ord"]==1)
{
	$ord = " member_name ASC";
	$img_name = "up";
	$ord_next_name = 2;
}
elseif(isset($_GET["ord"]) && $_GET["ord"]==2)
{
	$ord = " member_name DESC";
	$img_name = "down";
}
elseif(isset($_GET["ord"]) && $_GET["ord"]==3)
{
	$ord = " member_email ASC";
	$img_name = "up";
	$ord_next_email = 4;
}
elseif(isset($_GET["ord"]) && $_GET["ord"]==4)
{
	$ord = " member_email DESC";
	$img_name = "down";
}
else
{
	$_GET["ord"]=1;
	$ord = " member_name ASC";
	$ord_next_name = 2;
	$img_name = "up";
}
if($ord!="")
{
	$ord = " Order By " . $ord;
}


if (isset($_GET['member_active']))
{
if ($_GET['member_active'] == 0)
$sub .= " AND active = 0";
elseif ($_GET['member_active'] == 1)
$sub .= " AND active = 1";
elseif ($_GET['member_active'] == 2)
$sub .= "";
}else
$sub .= " AND active = 1";

/// setting pagination variables

if(isset($_GET['page']) && $_GET['page'] !='')
	$page=$_GET['page'];
else
	$page=1;

$qry="Select * from cpefs_members Where 1 $sub $ord ";

$variables = getAdminPagingVariables($qry,$page,$qryParams);

$toshow = $variables['toshow'];
$start = $variables['start'];
$totalPages = $variables['totalPages'];
$rows = $variables['rows'];
?>
<script language="javascript" src="include/popcalendar.js"></script>
<table width="98%" height="350" cellpadding="0"  cellspacing="0" border="0">
	<tr>
   		<td colspan="3">
			<table width="100%"  cellspacing="0" cellpadding="0">
				<tr>
			  		<td class="body_head1">&nbsp;</td>
        			<td align="center" class="body_bg1"><strong>Manage Members</strong></td>
			  		<td class="body_head2">&nbsp;</td>
				</tr>
			</table>
		</td>
 	</tr>
 	<tr>
    	<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    	<td width="99%"  align="center" valign="top">
	  		<table width="99%">
      			<tr>
        			<td width="33%" height="17" valign="top" class="candylink">
					<?php
					if($_SESSION['IS_ADMIN']==1)
						echo '<a href="add_member.php" class="link2">[Add New Member]</a>';
					?>
					<a href="member_emails_csv.php" class="link2">[Download All Member Emails]</a>
					<a href="member_emails_csv.php?status=0" class="link2">[Download Employee Member Emails]</a>
					</td>
      			    <td width="67%" align="right" valign="top" class="candylink"><span class="error">
      			      <?php
					   if(isset($_GET['opt']) && $_GET['opt']=='1')
							echo "Member Successfully Created";
					   elseif($op=='2')
							echo "Member Successfully Deleted/Updated";
					   elseif(isset($_GET['opt']) && $_GET['opt']=='2')
							echo "Member Successfully Modified";
                       elseif($msg!='')
                           echo $msg;
					  ?>
      			    </span></td>
					
      			</tr>
   			</table>
            <form action="member_listing.php" method="post">
			<table width="99%" height="60">
      			<tr>
        		  <td width="33%" valign="middle" class="heading">
				    Member Name: <input type="text" name="s_member_name" value="<?php if(isset($_GET["smn"])) echo $_GET['smn']?>" class="inptbox" size="30"  />
            &nbsp;&nbsp; Member Email:
            <input type="text" name="s_member_email" value="<?php if(isset($_GET["sme"]))echo $_GET['sme']?>" class="inptbox" size="40"  />&nbsp;&nbsp;
			Status:
			<select name="s_member_status">
			  <option <?php if(isset($_GET["status"]) && $_GET['status']=="")echo "Selected";?> value="">Ignore</option>
			  <option <?php if(isset($_GET["status"]) &&$_GET['status']=="0")echo "Selected";?>  value="0">Un-approved</option>
			  <option <?php if(isset($_GET["status"]) &&$_GET['status']=="2")echo "Selected";?>  value="2">Approved</option>
			</select>
			&nbsp;
			Show:
			<select name="s_member_active">
			  <option <?php if(isset($_GET["member_active"]) && $_GET['member_active']=="1")echo "Selected";?> value="1">Active</option>
			  <option <?php if(isset($_GET["member_active"]) && $_GET['member_active']=="0")echo "Selected";?>  value="0">Inactive</option>
			  <option <?php if(isset($_GET["member_active"]) && $_GET['member_active']=="2")echo "Selected";?>  value="2">All</option>
			</select>
			&nbsp;
            <input type="image" src="images/search.png" align="absmiddle" alt="Search" name="search1" title="Search" /></td>
      			</tr>
      			<tr>

          <td width="33%" valign="middle" class="heading"> &nbsp;&nbsp; </td>
      			</tr>
   			</table>
</form></td>
			<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
			</tr>
			<tr>
			<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
			<td height="100%" width="100%" align="center" valign="top">
			<form action="" method="post" name="frm" id="frm">
			
			 <table width="98%">
				   <tr align="center">
					<td width="57%" height="10" align="right" valign="bottom">
					</td>
					<td width="43%" height="10" colspan="3" align="right" valign="bottom">
					<?php pagination("member_listing.php","&smn=$smn&sme=$sme&ord=$_GET[ord]&member_active=$active_member",$totalPages,$page); ?>
					</td>
				  </tr>
			</table>
	 		<table width="1100" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse;margin-top:10px;margin-left:5px;" align="center">
			<tr>
			  <td>
				<table width="100%" border="1" cellpadding="4" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
					<tr>
					  <td width="8%" align="left" class="heading" ><a href="member_listing.php?smn=<?php echo $_GET["smn"]?>&sme=<?php echo $_GET["sme"]?>&ord=<?php echo $ord_next_name?>&member_active=<?php echo $active_member?>"  class="link3">Member Name</a>
					  <?php
						if( isset($_GET["ord"]) && ( $_GET["ord"]==1 || $_GET["ord"]==2))
						{
					  ?>
							&nbsp;	<a href="member_listing.php?smn=<?php if(isset($_GET["smn"])) echo $_GET["smn"]?>&sme=<?php if(isset($_GET["sme"])) echo $_GET["sme"]?>&ord=<?php echo $ord_next_name?>&member_active=<?php echo $active_member?>" class="link2"><img src="images/<?php echo $img_name?>.gif" width="12" height="11" alt="^" border="0"></a>
					  <?php
						}
					  ?></td>
					  <td width="11%" align="left" class="heading">Street</a></td>
					  <td width="7%" align="left" class="heading">Suburb</a></td>
					  <td width="5%" align="center" class="heading">PCode</a></td>
					  <td width="8%" align="left" class="heading">Phone</a></td>
					  <td width="20%" align="left" class="heading" ><a href="member_listing.php?smn=<?php echo $_GET["smn"]?>&sme=<?php echo $_GET["sme"]?>&ord=<?php echo $ord_next_email?>&member_active=<?php echo $active_member?>"  class="link3">Email Address</a>
					  <?php
					  if( isset($_GET["ord"]) && ($_GET["ord"]==3 || $_GET["ord"]==4))
					  {
					  ?>
							&nbsp;<a href="member_listing.php?smn=<?php echo $_GET["smn"]?>&sme=<?php echo $_GET["sme"]?>&ord=<?php echo $ord_next_email?>&member_active=<?php echo $active_member?>" class="link2"><img src="images/<?php echo $img_name?>.gif" width="12" height="11" alt="^" border="0"></a>
					  <?php
					  }
					  ?>
					  </td>
					 <td width="6%"  align="center" class="heading" >Manage </td>
				  <td width="7%"  align="center" class="heading" >
					  <input name="delete_all" title="Delete All Members" onclick="delete_chk_all()" type="checkbox" />&nbsp;Delete
					  </td>
					 <td width="9%"  align="center" class="heading">Status</td>
					 <td width="9%"  align="center" class="heading">Payment Amount</td>
					 <td width="10%"  align="center" class="heading">Current To</td> 
					 	 <td width="10%"  align="center" class="heading">Active</td> 
				  </tr>
				   <?php
						if($rows>0)
						{
								$sql=$qry." limit $start,$toshow";
								$res=$dbc->getResultPDO($sql,__LINE__,__FILE__,$qryParams);
								$color="";
								while($row=$res->fetch(PDO::FETCH_ASSOC))
								{
									if($color=="bgc")
										$color="bgc2";
									else
										$color="bgc";
									?>
							<tr class="<?php echo $color?>">
								<td align="left" style="padding-left:2px;"> <a name = "<?php echo $row["member_id"]?>"></a>
								  <a href="add_member.php?mode=edit&id=<?php echo $row["member_id"]?>" title="Edit <?php echo show_text($row["member_name"])?>" class="link2"><?php echo show_text($row["member_name"])?></a>
								</td>
								<td align="left" style="padding-left:2px;">
								  <?php echo show_text($row["member_address"])?>
								</td>
								<td align="left" style="padding-left:2px;">
								  <?php echo show_text($row["member_suburb"])?>
								</td>
								<td align="center" style="padding-left:2px;">
								  <?php echo show_text($row["member_postcode"])?>
								</td>
								<td align="left" style="padding-left:2px;">
								  <?php echo show_text($row["member_telephone"])?>
								</td>
								<td align="left" style="padding-left:2px;">
									<?php echo show_text($row["member_email"])?>
								</td>
								<td align="center">
								<?php
								if($_SESSION['IS_ADMIN']==1)
								{
									if($row["member_status"]==2)
									{

										echo "<a href='member_listing.php?id=$row[member_id]&email=$row[member_email]&reset_pass=true' title='Reset Password' class='link2'>Reset Pwd</a>";

									}
									if($row["member_status"]==0)
									{
									?>
										<a href="approve_membership.php?id=<?php echo $row["member_id"]?>" title="Approve Membership" class="link2">Approve</a>
									<?php
									}
								}
								else
									echo "N/A";
								?>
								</td>
								<td align="center">
									
									<input name="delete[]" type="checkbox" value="<?php echo $row["member_id"]?>" />
								</td>
								<td align="center" class="header">
								<input type="hidden" name="id[]" value="<?php echo $row['member_id']?>" />
								 <select name="status[]" class="dropdown">
								  <option value="0" <?php if($row["status"]==0) echo "Selected";?>>Employee</option>
								  <option value="1" <?php if($row["status"]==1) echo "Selected";?>>Associate</option>
								  <option value="2" <?php if($row["status"]==2) echo "Selected";?>>Life</option>
								 </select>
								</td>
								<td align="center" class="header">
								 <input type="text" name="pay[]" value="<?php echo $row['payment_amt']?>" class="inptbox" size="10" />
								</td>
								<td align="center" class="header">
								 <input type="text" name="current_to[]" value="<?php echo $row['current_to']?>" class="inptbox" size="10" />
								</td>
								
								<td align="center">
								 <select name="active[]" class="dropdown">
								  <option value="0" <?php if($row["active"]==0) echo "Selected";?>>Inactive</option>
								  <option value="1" <?php if($row["active"]==1) echo "Selected";?>>Active</option>

								</td>
							</tr>
						  <?php
						  }
						}
						else
						{
						?>
						  <tr>
							 <td align="center" colspan="10" valign="middle" class="error" >
								<b>No Members Found!</b>
							 </td>
						  </tr>
						<?php
						}
						?>
			  </table>
			  </td>
				 </tr>
			   </table><br />
			   <table width="98%">
				   <tr align="center">
					<td width="57%" height="10" align="right" valign="bottom">
					<?php
					if($_SESSION['IS_ADMIN']==1)
						echo '<input name="submit" type="submit" class="button1" value="Submit" />';
					?>
					</td>
					<td width="43%" height="10" colspan="3" align="right" valign="bottom">
					<?php pagination("member_listing.php","&smn=$smn&sme=$sme&ord=$_GET[ord]&member_active=$active_member",$totalPages,$page); ?>

					</td>
				  </tr>
				</table>
			</form>
<?php include "include/admin_footer.php";?>
