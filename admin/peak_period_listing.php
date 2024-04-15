<?php
include "include/admin_header.php";
include_once "redirect_to_adminlogin.php";

$_SESSION['backpage']="peak_period_listing.php";
$f=0;
$op = 0;
global $dbc;
if(isset($_POST['submit']))
{
	/* Multiple Deletion */
	if(isset($_POST['delete']) && $_POST['delete'] !="")
	{
		foreach($_POST['delete'] as $del)
		{
			$dbc->delete(" Delete from cpefs_peak_periods where peak_period_id = ?",__LINE__,__FILE__,array("i",&$del));
		}
	}
	$op=2;
}

$toshow = 1;
$totalPages = 1;
$ord="";
$ord_next_start_date = 1;
$ord_next_end_date = 3;
$img_name="";
if(isset($_GET["ord"])) {
    if ($_GET["ord"] == 1) {
        $ord = " peak_period_start_date ASC";
        $img_name = "up";
        $ord_next_start_date = 2;
    } elseif ($_GET["ord"] == 2) {
        $ord = " peak_period_start_date DESC";
        $img_name = "down";
    } elseif ($_GET["ord"] == 3) {
        $ord = " peak_period_end_date ASC";
        $img_name = "up";
        $ord_next_end_date = 4;
    } elseif ($_GET["ord"] == 4) {
        $ord = " peak_period_end_date DESC";
        $img_name = "down";
    }
}
else
{
	$_GET["ord"]=1;
	$ord = " peak_period_start_date ASC";
	$ord_next_start_date = 2;
	$img_name = "up";
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
        			<td align="center" class="body_bg1"><strong>Manage Peak Periods</strong></td>
			  		<td class="body_head2">&nbsp;</td>
				</tr>
			</table>
		</td>
 	</tr>
 	<tr>
    	<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    	<td width="99%"  align="center" valign="top">
	  		<table width="75%">
      			<tr>
        			<td width="33%" height="17" valign="top" class="candylink">
					<?php
					if($_SESSION['IS_ADMIN']==1)
						echo '<a href="add_peak_period.php" class="link2">[Add New Peak Period]</a>';
					?>	
					</td>
      			    <td width="67%" align="right" valign="top" class="candylink"><span class="error">
      			      <?php
					   if(isset($_GET['opt']) && $_GET['opt']=='1')
							echo "Peak Period Successfully Created";
					   elseif($op=='2')
							echo "Peak Period Successfully Deleted";
					   elseif(isset($_GET['opt']) && $_GET['opt']=='2')
							echo "Peak Period Successfully Modified";
					  ?>
      			    </span></td>
      			</tr>
   			</table>
			</td>
			<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
			</tr>
			<tr>
			<td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
			<td height="340" width="100%" align="center" valign="top">
			<form action="" method="post" name="frm" id="frm">
	 		<table width="75%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse;margin-top:10px;" align="center">
	 			<tr>
	 				<td>
						<table width="100%" border="1" cellpadding="4" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
							<tr>
							  <td width="40%" align="left" class="heading" style="padding-left:10px;"  ><a href="peak_period_listing.php?ord=<?php echo $ord_next_start_date?>"  class="link3">Start Date(Mon)</a>
							  <?php
							  	if(isset($_GET["ord"]) && ($_GET["ord"]==1 || $_GET["ord"]==2))
								{
							  ?>
									&nbsp;	<a href="peak_period_listing.php?ord=<?php echo $ord_next_start_date?>" class="link3"><img src="images/<?php echo $img_name?>.gif" width="12" height="11" alt="^" border="0"></a>
							  <?php
								}
							  ?>
							  
							  </td>
							  <td width="40%" align="left" class="heading" style="padding-left:10px;"  ><a href="peak_period_listing.php?ord=<?php echo $ord_next_end_date?>"  class="link3">End Date(Sun)</a>
							  <?php
							  	if(isset($_GET["ord"]) && ($_GET["ord"]==1 || $_GET["ord"]==2))
								{
							  ?>
									&nbsp;	<a href="peak_period_listing.php?ord=<?php echo $ord_next_end_date?>" class="link3"><img src="images/<?php echo $img_name?>.gif" width="12" height="11" alt="^" border="0"></a>
							  <?php
								}
							  ?>
							  
							  </td>
							  <td width="10%" align="center" class="heading">Edit</td>
							  <td width="10%"  align="center" class="heading" >
							  <input name="delete_all" title="Delete All Periods" onclick="delete_chk_all()" type="checkbox" />&nbsp;Delete
							  </td>
						  </tr>
						   <?php
								if(isset($_GET['page']) && $_GET['page'] !='')
									$page=$_GET['page'];
								else {
                                    $page = 1;
                                    $_GET['page']=1;
                                }
					
                                $qry="Select * from cpefs_peak_periods $ord ";
                                $variables = getAdminPagingVariables($qry,$page);

                                $toshow = 25; //overwriting admin settings;
                                $start = $variables['start'];
                                $totalPages = $variables['totalPages'];
                                $rows = $variables['rows'];

								if($rows>0)
								{
										$sql=$qry." limit $start,$toshow";
										$res=$dbc->getResult($sql,__LINE__,__FILE__);
										$color ="";
										while($row=mysqli_fetch_array($res))
										{
											if($color=="bgc")
												$color="bgc2";
											else 
												$color="bgc";
											?>
									<tr class="<?php echo $color?>">
										<td align="left" style="padding-left:10px;"> <a name = "<?php echo $row["peak_period_id"]?>"></a>
										 <?php echo $row["peak_period_start_date"]?>
										</td>
										<td align="left" style="padding-left:10px;">
										  <?php echo $row["peak_period_end_date"]?>
										</td>
										<td align="center"> 
										<?php
										if($_SESSION['IS_ADMIN']==1)
										{
										?>
										 <a href="add_peak_period.php?id=<?php echo $row["peak_period_id"]?>" title="Edit Period" class="link2">Edit</a>
										<?php
										}
										else
											echo "N/A";
										?> 
										</td>
										<td align="center">
											<input type="hidden" name="id[]" value="<?php echo $row['peak_period_id']?>" />
											<input name="delete[]" type="checkbox" value="<?php echo $row["peak_period_id"]?>" />
										</td>
									</tr>
								  <?php
								  }
								} 
								else
								{
								?>
								  <tr>
									 <td align="center" colspan="7" valign="middle" class="error" >
										<b>No Peak Periods Found!</b>
									 </td>
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
							<?php pagination("peak_period_listing.php","&ord=$_GET[ord]",$totalPages,$page); ?>
						</td>
					  </tr>
					</table>	  
				</form>
<?php include "include/admin_footer.php";?>
