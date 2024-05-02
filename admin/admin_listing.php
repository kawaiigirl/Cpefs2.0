<?php
include "include/admin_header.php";
include_once "redirect_to_adminlogin.php";

$_SESSION['backpage']="admin_listing.php";

global $dbc;
$f=0;
$sub = "";
$qryParamTypes = "";
$qryParams = array();
$op = 0;
if(isset($_POST['submit']))
{
	/* Multiple Deletion */
	if($_POST['delete'] !="")	
	{
		foreach($_POST['delete'] as $del)
		{
			$dbc->deletePDO(" Delete from cpefs_admin where id = :id",__LINE__,__FILE__,array("id"=>$del));
		}
	}
	$op=2;
}
elseif(count($_POST)>0 || isset($_GET['san']) || isset($_GET['sae']))
{
	if(count($_POST)>0)
	{
		$_GET['san']=$_POST['s_admin_name'];
		$_GET['sae']=$_POST['s_admin_email'];
	}
	if(isset($_GET['san']) && $_GET['san']!="")
	{
		$sub=" and name like ?";
        $qryParamTypes .="s";
        $qryParams[] = "%" . $_GET["san"] . "%";
	}	
	if(isset($_GET['sae']) && $_GET['sae']!="")
	{
		$sub .=" and email = ?";
        $qryParamTypes .="s";
        $qryParams[] = $_GET["sae"];
	}	
	
}
$ord = "";
$img_name = "";
$ord_next_name = 0;
$ord_next_email = 0;
if(isset($_GET["ord"])) {
    if ($_GET["ord"] == 1) {
        $ord = " name ASC";
        $img_name = "up";
        $ord_next_name = 2;
        $ord_next_email = 3;
    } elseif ($_GET["ord"] == 2) {
        $ord = " name DESC";
        $img_name = "down";
        $ord_next_name = 1;
        $ord_next_email = 3;
    } elseif ($_GET["ord"] == 3) {
        $ord = " email ASC";
        $img_name = "up";
        $ord_next_name = 1;
        $ord_next_email = 4;
    } elseif ($_GET["ord"] == 4) {
        $ord = " email DESC";
        $img_name = "down";
        $ord_next_name = 1;
        $ord_next_email = 3;
    }
}
else
{
	$_GET["ord"]=1;
	$ord = " name ASC";
	$img_name = "up";
	$ord_next_name = 2;
	$ord_next_email = 3;
}
if($ord!="")
{
	$ord = " Order By " . $ord;
}

?>
<table width="98%" height="350" cellpadding="0"  cellspacing="0" border="0">
	<tr>
   		<td colspan="3">
			<table width="100%"  cellspacing="0" cellpadding="0">
				<tr>
			  		<td class="body_head1">&nbsp;</td>
        			<td align="center" class="body_bg1"><strong>Manage Administrators</strong></td>
			  		<td class="body_head2">&nbsp;</td>
				</tr>
			</table>
		</td>
 	</tr>
 	<tr>
    	<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    	<td width="95%" height="100%" align="center" valign="top">
	  		<table width="95%" height="17">
      			<tr>
        			<td width="33%" height="17" valign="top" class="candylink">
					<?php
					if($_SESSION['IS_ADMIN']==1)
						echo '<a href="add_admin.php" class="link2">[Add New Admin]</a>';
					?>
					</td>
      			    <td width="67%" align="right" valign="top" class="candylink"><span class="error">
      			      <?php
					   if(isset($_GET['opt']) && $_GET['opt']=='1')
							echo "Administrator Successfully Created";
					   elseif($op=='2')
							echo "Administrator  Successfully Deleted";
					   elseif(isset($_GET['opt']) && $_GET['opt']=='2')
							echo "Administrator Successfully Modified";
					  ?>
      			    </span></td>
      			</tr>
   			</table>
            <form action="" method="post">
			<table width="95%" height="10">

      			<tr>
        		  <td width="33%" valign="middle" class="heading">
				    Administrator Name: <input type="text" name="s_admin_name" value="<?php if(isset($_GET['san'])) echo $_GET['san'];?>" class="inptbox" size="30"  />&nbsp;&nbsp;
					<input type="image" src="images/search.png" align="absmiddle" alt="Search" name="search" title="Search" />
				  </td>
      			</tr>
      			<tr>
        		  <td width="33%" valign="middle" class="heading">
				    Administrator Email: <input type="text" name="s_admin_email" value="<?php if(isset($_GET['sae'])) echo $_GET['sae'];?>" class="inptbox" size="100"  />&nbsp;&nbsp;
					<input type="image" src="images/search.png" align="absmiddle" alt="Search" name="search1" title="Search" />
				  </td>
      			</tr>

   			</table>
            </form>
        </td><td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td></tr>
			<tr>
			<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
			<td height="100%" width="100%" align="center" valign="top">
			<form action="" method="post" name="frm" id="frm">
	 		<table width="95%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
	 			<tr>
	 				<td>
						<table width="100%" border="1" cellpadding="4" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
							<tr>
							  <td width="19%" align="left" class="heading"  ><a href="admin_listing.php?san=<?php if(isset($_GET['san'])) echo $_GET['san'];?>&sae=<?php if(isset($_GET['sae'])) echo $_GET['sae'];?>&ord=<?php echo $ord_next_name?>"  class="link1">Admin Name</a>
							  <?php
							  	if($_GET["ord"]==1 || $_GET["ord"]==2)
								{
							  ?>
									&nbsp;	<a href="admin_listing.php?san=<?php if(isset($_GET['san'])) echo $_GET['san'];?>&sae=<?php if(isset($_GET['sae'])) echo $_GET['sae'];?>&ord=<?php echo $ord_next_name?>" class="link2"><img src="images/<?php echo $img_name?>.gif" width="12" height="11" alt="^" border="0"></a>
							  <?php
								}
							  ?>							  </td>
							  <td width="57%" align="left" class="heading" ><a href="admin_listing.php?san=<?php if(isset($_GET['san'])) echo $_GET['san'];?>&sae=<?php if(isset($_GET['sae'])) echo $_GET['sae'];?>&ord=<?php echo $ord_next_email?>"  class="link1">Email Address</a>
							  <?php
							  	if($_GET["ord"]==3 || $_GET["ord"]==4)
								{
							  ?>
									&nbsp;	<a href="admin_listing.php?san=<?php if(isset($_GET['san'])) echo $_GET['san'];?>&sae=<?php if(isset($_GET['sae'])) echo $_GET['sae'];?>&ord=<?php echo $ord_next_email?>" class="link2"><img src="images/<?php echo $img_name?>.gif" width="12" height="11" alt="^" border="0"></a>
							  <?php
								}
							  ?>							  </td>
							  <td width="13%" align="center" class="heading">Account Type</td>
							  <td width="11%"  align="center" class="heading" >
							  <input name="delete_all" title="Delete All Administrators" onclick="delete_chk_all()" type="checkbox" />&nbsp;Delete							  </td>
						  </tr>
						   <?php
								if(isset($_GET['page']) && $_GET['page'] !='')
									$page=$_GET['page'];
								else
									$page=1;
					
								$qry="Select * from cpefs_admin Where 1 $sub $ord ";

								$variables = getAdminPagingVariables($qry,$page,$qryParams,$qryParamTypes);

                                $toshow = $variables['toshow'];
                                $start = $variables['start'];
                                $totalPages = $variables['totalPages'];
                                $rows = $variables['rows'];
                                $qryParamsRefs = $variables['paramsRefs'];
								if($rows>0)
								{

										$sql=$qry." limit $start,$toshow";
										$res=$dbc->getResult($sql,__LINE__,__FILE__,$qryParamsRefs);
										$color = "bgc2";
										if($res)
                                        {
                                            while($row= $res->fetch_array(MYSQLI_ASSOC))
                                            {
                                                if($color=="bgc")
                                                    $color="bgc2";
                                                else
                                                    $color="bgc";
                                                ?>
                                        <tr class="<?php echo $color?>">
                                            <td align="left" style="padding-left:10px;"> <a name = "<?php echo $row["id"]?>"></a>
                                              <a href="add_admin.php?mode=edit&id=<?php echo $row["id"]?>" title="Edit <?php echo $row["name"]?>" class="link2"><?php echo show_text($row["name"])?></a>										</td>
                                            <td align="left" style="padding-left:10px;padding-right:10px;">
                                              <?php echo show_text($row["email"])?>
                                            </td>
                                            <td align="center">
                                             <?php
                                             if($row["is_admin"]==1)
                                                echo "Admin";
                                             else
                                                echo "Director";
                                             ?>
                                            </td>
                                            <td align="center">
                                            <input type="hidden" name="id[]" value="<?php echo $row['id']?>" />
                                            <?php
                                                if($row["id"]!=1)
                                                {
                                            ?>
                                                <input name="delete[]" type="checkbox" value="<?php echo $row["id"]?>" />
                                            <?php
                                                }
                                            ?>										</td>
                                        </tr>
                                      <?php
                                      }
                                    }
                                }
								else
								{
								?>
								  <tr>
									 <td align="center" colspan="8" valign="middle" class="error" >
										<b>No Administrators Found!</b>									 </td>
								  </tr>
								<?php
								}
								?>
						  </table>
			      </td>
					 </tr>
				   </table><br />
				   <table width="60%">
					   <tr align="center"> 
						<td width="57%" height="10" align="right" valign="bottom"> 
						<?php
						if($_SESSION['IS_ADMIN']==1)
							echo '<input name="submit" type="submit" class="button1" value="Submit" />';
						?>
						</td>
						<td width="43%" height="10" colspan="3" align="right" valign="bottom" class="link1">
                            <?php pagination("admin_listing.php","&san=$_GET[san]&sae=$_GET[sae]&ord=$_GET[ord]",$totalPages,$page);?>
						</td>
					  </tr>
					</table>	  
				</form>
<?php include "include/admin_footer.php";?>
