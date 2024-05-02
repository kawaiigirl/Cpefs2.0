<?php
include "include/admin_header.php";
include_once "redirect_to_adminlogin.php";
$_SESSION['backpage']="booking_listing.php";
$msg ="";
$sub = "";
$f=0;
global $dbc;

if(isset($_POST['submit']))
{
    $count = count($_POST["booking_id"]);
    for($i=0;$i<$count;$i++)
    {
        $booking_id = $_POST["booking_id"][$i];
        $is_peak = $_POST["is_peak"][$i];
        $dbc->update("Update cpefs_booking Set is_peak_period='$is_peak' Where booking_id=?",__LINE__,__FILE__,array("i",&$booking_id));
    }
}
elseif(isset($_GET['act']))
{
    if($_GET['act']=="delete")
    {
        $bookingId = $_GET['id'];
        if(isset($_GET['email'])){
            $sendEmail = $_GET['email'];
        }else{
            $sendEmail = 'true';
        }
        $msg = deleteBooking($bookingId,$sendEmail,'booking_listing');
    }
    else
    {
        $ssql="Select cpefs_booking.*,cpefs_members.*,cpefs_units.unit_name,cpefs_units.unit_location, cpefs_units.manager_email From cpefs_booking 
			   Inner Join cpefs_members On cpefs_members.member_id=cpefs_booking.member_id 
			   Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id Where booking_id=?";
        $bookingId= $_GET['id'];

        $data=$dbc->getSingleRow($ssql,__LINE__,__FILE__,array("i",&$bookingId));

        if($_GET['act']=="approve")
        {
            $msg .= approveBooking($bookingId,$data,'booking_listing');
        }
        elseif($_GET['act']=="confirm")
        {
            $msg .= confirmBooking($bookingId,$data,'booking_listing');
            if($data['rate']==$data['paid'])	// Full payment is done and confirmmed
            {
                $msg .="<br>";
                $msg .= sendFullPaymentBookingEmail($bookingId,$data,'booking_listing');
            }
        }
        elseif($_GET['act']=="cancel")
        {
            $msg .= cancelBooking($bookingId,$data,'booking_listing');
        }
    }
}
$qrystringord="";
$qryParamTypes = "";
$qryParams = array();
if(count($_POST)>0 || (isset($_GET['unit']) && $_GET['unit']!="") ||  (isset($_GET['mem']) && $_GET['mem']!="") || (isset($_GET['cdate']) && $_GET['cdate']!="") || (isset($_GET['status']) && $_GET['status']!=""))
{
    if(count($_POST)>0)
    {
        $_GET['page']=1;

        if(isset($_POST['unit']))
            $_GET['unit']=$_POST['unit'];
        elseif(!isset($_GET['unit']))
            $_GET['unit']="";
        if(isset($_POST['mem']))
            $_GET['mem']=$_POST['mem'];
        elseif(!isset($_GET['mem']))
            $_GET['mem']="";
        if(isset($_POST['status']))
            $_GET['status']=$_POST['status'];
        elseif(!isset($_GET['status']))
            $_GET['status']="";
        if(isset($_POST['check_in_date'])) {
            $_GET['cdate'] = $_POST['check_in_date'];
        }
        elseif(!isset($_GET['cdate'])) {
            $_GET['cdate'] = "";
        }
    }
    if(isset($_GET['unit']) && $_GET['unit']!="")
    {
        $qrystringord="&unit=$_GET[unit]";
        $sub=" And cpefs_booking.unit_id=?";
        $qryParamTypes .="i";
        $qryParams[] = $_GET["unit"];
    }
    if(isset($_GET['mem']) && $_GET['mem']!="")
    {
        $qrystringord .="&mem=$_GET[mem]";
        $sub .=" And cpefs_members.member_name Like ?";
        $qryParamTypes .="s";
        $qryParams[] = "%" . $_GET["mem"] . "%";
    }
    if(isset($_GET['cdate']) && $_GET['cdate']!="")
    {
        $qrystringord .="&cdate=$_GET[cdate]";
        $sub .=" And check_in_date = ?";
        $qryParamTypes .="s";
        $qryParams[] = $_GET["cdate"];
    }
    if(isset($_GET['status']) && $_GET['status']!="")
    {
        $qrystringord .="&status=$_GET[status]";
        $sub .=" And approve = ?";
        $qryParamTypes .="i";
        $qryParams[] = $_GET["status"];
    }
}

$ord_next_unit = 1;
$ord_next_mem = 3;
$ord_next_cdate = 5;
$img_name ="down";
$ord = "";
if(isset($_GET["ord"]))
{
    switch($_GET["ord"])
    {
        case 1 :
            $ord = " cpefs_booking.unit_id DESC";
            $img_name = "down";
            $ord_next_unit = 2;
            break;
        case 2 :
            $ord = " cpefs_booking.unit_id ASC";
            $img_name = "up";
            break;
        case 3 :
            $ord = " member_name DESC";
            $img_name = "down";
            $ord_next_mem = 4;
            break;
        case 4 :
            $ord = " member_name ASC";
            $img_name = "up";
            break;
        case 5 :
            $ord = " check_in_date DESC";
            $img_name = "down";
            $ord_next_cdate = 6;
            break;
        case 6 :
            $ord = " check_in_date ASC";
            $img_name = "up";
            break;
    }
}
if($ord!="")
{
    $qstringall = $qrystringord. "&ord=$_GET[ord]";
    $ord = " Order By " . $ord;
}
else
{
    $qstringall = $qrystringord;
    $ord = " Order By booking_date";
}

$querystring = $qstringall;


$qry="Select cpefs_booking.*,cpefs_members.member_name,cpefs_units.unit_name From cpefs_booking 					  
					Inner Join cpefs_members On cpefs_members.member_id=cpefs_booking.member_id
					Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id
					Where 1 $sub $ord ";




if(isset($_GET['page']) && $_GET['page'] !='')
    $page=$_GET['page'];
else
    $page="highest";

$variables = getAdminPagingVariables($qry,$page,$qryParams,$qryParamTypes);

$page = $variables['page'];
$toshow = 25; //overwriting admin settings;
$start = $variables['start'];
$totalPages = $variables['totalPages'];
$rows = $variables['rows'];
$qryParamsRefs = $variables['paramsRefs'];



/*************************/

$qrystringord .= "&page=$page";

$qstringall .= "&page=$page";
?>
    <script language="javascript" src="scripts/cal2.js">
        /*

        Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)

        Script featured on/available at http://www.dynamicdrive.com/

        This notice must stay intact for use

        */
    </script>

    <script language="javascript" src="scripts/cal_conf2.js"></script>

    <script LANGUAGE="JavaScript">

        function confirmPost(id,page)
        {
            let agree = confirm("Are you sure you want to delete?");
            if (agree)
            {
                let yes=confirm("Do you want to send a notification email?");
                if(yes)
                {
                    window.location.href = 'booking_listing.php?id='+ id + page +'&act=delete&email=true';

                    return false ;
                }
                else
                {
                    window.location.href = 'booking_listing.php?id='+ id + page +'&act=delete&email=false';
                    return false ;
                }

            }
            else
                return false ;
        }

    </script>

    <table width="98%" height="350" cellpadding="0"  cellspacing="0" border="0">
        <tr>
            <td colspan="3">
                <table width="100%"  cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="body_head1">&nbsp;</td>
                        <td align="center" class="body_bg1"><strong>Manage Bookings</strong></td>
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
                                echo '<a href="add_booking.php" class="link2">[Add Manual Booking]</a>';
                            echo '<a href="deleted_bookings.php" class="link2">&nbsp;&nbsp;[View Deleted Bookings]</a>';
                            ?>
                        </td>
                        <td width="67%" align="right" valign="top" class="candylink"><span class="error">
      			      <?php
                      if(isset($_GET['opt']) && $_GET['opt']=='1')
                          echo "Booking Successfully Created";
                      elseif(isset($_GET['opt']) && $_GET['opt']=='2')
                          echo "Booking Successfully Modified";
                      elseif($msg!='')
                          echo $msg;
                      ?>
      			    </span></td>
                    </tr>
                </table>
                <form action="booking_listing.php" method="post" name="form2" id="form2">
                <table width="99%" height="30">

                        <tr>
                            <td width="33%" valign="middle" class="heading"> Unit Name:
                                <select name="unit" id="unit">
                                    <option <?php if(isset($_GET['unit']) && $_GET['unit']=="")echo "Selected";?> value="">Please select</option>
                                    <?php
                                    $res=$dbc->getResult("Select * From cpefs_units Where unit_status=1 Order By unit_name",__LINE__,__FILE__);
                                    while($row=mysqli_fetch_array($res))
                                    {
                                        ?>
                                        <option <?php if(isset($_GET['unit']) && $_GET['unit']==$row['unit_id'])echo "Selected";?> value="<?php echo $row['unit_id']?>">
                                            <?php echo show_text($row['unit_name'])?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>&nbsp;

                                Member Name: <input type="text" name="mem" value="<?php if(isset($_GET['mem'])) echo $_GET['mem']?>" class="inptbox" size="25" />

                                Check-In-Date: <input type="text" name="check_in_date" value="<?php if(isset($_GET['cdate']))echo $_GET['cdate']?>" class="inptbox" size="20" />

                                <a href="javascript:showCal('Calendar1')"><img src="images/calendar.png" align="absmiddle" alt="Select Check-in-date" border="0"></a>&nbsp;

                                Status:
                                <select name="status">
                                    <option <?php if(isset($_GET['status']) && $_GET['status']=="")echo "Selected";?> value="">Please select</option>
                                    <option <?php if(isset($_GET['status']) &&$_GET['status']=="0")echo "Selected";?>  value="0">Un-approved</option>
                                    <option <?php if(isset($_GET['status']) &&$_GET['status']=="1")echo "Selected";?>  value="1">Pending</option>
                                    <option <?php if(isset($_GET['status']) &&$_GET['status']=="2")echo "Selected";?>  value="2">Confirmed</option>
                                    <option <?php if(isset($_GET['status']) &&$_GET['status']=="3")echo "Selected";?>  value="3">Cancelled</option>
                                </select>
                                &nbsp;
                                <input type="image" src="images/search.png" align="absmiddle" alt="Search" title="Search" name="search1" />
                            </td>
                        </tr>

                </table>
                </form>
            </td>
            <td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
        </tr>
        <tr>
            <td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
            <td height="100%" width="100%" align="center" valign="top">
                <form action="" method="post" name="frm" id="frm">
                    <table width="99%">
                        <tr align="center">
                            <td width="57%" height="10" align="right" valign="bottom">
                            </td>
                            <td width="43%" height="10" colspan="3" align="right" valign="bottom">
                                <?php pagination("booking_listing.php",$querystring,$totalPages,$page);//echo $querystring;?>
                            </td>
                        </tr>
                    </table>
                    <table width="99%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse;margin-top:10px;" align="center">
                        <tr>
                            <td>
                                <table width="100%" border="1" cellpadding="7" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
                                    <tr>
                                        <td width="8%" align="left" class="heading" style="padding-left:5px;">
                                            <a href="booking_listing.php?ord=<?php echo $ord_next_unit?><?php echo $qrystringord?>" class="link3">Unit Name</a>
                                            <?php
                                            if(isset($_GET["ord"]) && ($_GET["ord"]==1 || $_GET["ord"]==2))
                                            {
                                                ?>
                                                &nbsp;<a href="booking_listing.php?ord=<?php echo $ord_next_unit?><?php echo $qrystringord?>" class="link2">
                                                <img src="images/<?php echo $img_name?>.gif" width="12" height="11" alt="^" border="0"></a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td width="14%" align="left" class="heading" style="padding-left:5px;">
                                            <a href="booking_listing.php?ord=<?php echo $ord_next_mem?><?php echo $qrystringord?>" class="link3">Member Name</a>
                                            <?php

                                            if(isset($_GET["ord"]) && ($_GET["ord"]==3 || $_GET["ord"]==4))
                                            {
                                                ?>
                                                &nbsp; <a href="booking_listing.php?ord=<?php echo $ord_next_mem?><?php echo $qrystringord?>" class="link2"><img src="images/<?php echo $img_name?>.gif" width="12" height="11" alt="^" border="0"></a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td width="11%" align="center" class="heading" style="padding-left:5px;"><a href="booking_listing.php?ord=<?php echo $ord_next_cdate?><?php echo $qrystringord?>" class="link3">Check In Date</a>
                                            <?php
                                            if(isset($_GET["ord"]) && ($_GET["ord"]==5 || $_GET["ord"]==6))
                                            {
                                                ?>
                                                &nbsp; <a href="booking_listing.php?ord=<?php echo $ord_next_cdate?><?php echo $qrystringord?>" class="link2"><img src="images/<?php echo $img_name?>.gif" width="12" height="11" alt="^" border="0"></a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td width="7%" align="center" class="heading">Rate</td>
                                        <td width="7%" align="center" class="heading">Deposit</td>
                                        <td width="7%" align="center" class="heading">Balance</td>
                                        <td width="5%" align="center" class="heading"># Nights</td>
                                        <td width="10%" align="center" class="heading">Date Booked</td>
                                        <td width="10%" align="center" class="heading">Date Confirmed</td>
                                        <td width="6%" align="center" class="heading">Period</td>
                                        <td width="7%"  align="center" class="heading" >Status</td>
                                        <td width="6%"  align="center" class="heading" >See Payments</td>
                                        <td width="6%"  align="center" class="heading" >Manage </td>
                                    </tr>
                                    <?php
                                    if($rows>0)
                                    {
                                        $sql=$qry." limit $start,$toshow";
                                        $res = $dbc->getResult($sql,__LINE__,__FILE__,$qryParamsRefs);
                                        $color = "";
                                        while($row=$res->fetch_array(MYSQLI_ASSOC))
                                        {
                                            if($color=="bgc")
                                                $color="bgc2";
                                            else
                                                $color="bgc";

                                            ?>
                                            <tr class="<?php echo $color?>">
                                                <td align="left" style="padding-left:2px;">
                                                    <a href="add_booking.php?id=<?php echo $row['booking_id'];?>" title="Edit <?php echo show_text($row["member_name"]); ?>" class="link2">
                                                        <?php echo show_text($row["unit_name"])?>
                                                    </a>
                                                </td>
                                                <td align="left" style="padding-left:2px;">
                                                    <a href="add_member.php?mode=edit&id=<?php echo $row["member_id"]?>" title="Edit <?php echo show_text($row["member_name"])?>" class="link2">
                                                        <?php echo show_text($row["member_name"])?>
                                                    </a> </td>
                                                <td align="center" style="padding-left:2px;">
                                                    <?php echo date("d M Y",strtotime($row["check_in_date"]));?>
                                                </td>
                                                <td align="center" style="padding-left:2px;">
                                                    <?php echo "$".$row["rate"]?>
                                                </td>
                                                <td align="center" style="padding-left:2px;">
                                                    <?php
                                                    if($row["paid"]=="0.00")
                                                        echo "Nil";
                                                    else
                                                        echo "$".number_format($row["paid"],2);
                                                    ?>
                                                </td>
                                                <td align="center" style="padding-left:2px;">
                                                    <?php
                                                    if($row["rate"]==$row["paid"])
                                                        echo "Nil";
                                                    else
                                                        echo "$".number_format(($row["rate"]-$row["paid"]),2);
                                                    ?>
                                                </td>
                                                <td align="center" style="padding-left:2px;">
                                                    <?php echo $row["nights"]?>
                                                </td>
                                                <td align="center" style="padding-left:2px;">
                                                    <?php echo date("d M Y",strtotime($row["booking_date"]));?>
                                                </td>
                                                <td align="center" style="padding-left:2px;">
                                                    <?php
                                                    if($row["confirm_date"]!="")
                                                        echo date("d M Y",strtotime($row["confirm_date"]));
                                                    else
                                                        echo "-";
                                                    ?>
                                                </td>
                                                <td align="center" class="header">
                                                    <input type="hidden" name="booking_id[]" value="<?php echo $row['booking_id']?>" />
                                                    <select name="is_peak[]" class="dropdown">
                                                        <option value="S" <?php if($row["is_peak_period"]=="S") echo "Selected";?>>Standard</option>
                                                        <option value="P" <?php if($row["is_peak_period"]=="P") echo "Selected";?>>Peak</option>
                                                    </select>
                                                </td>
                                                <td align="center">
                                                    <?php
                                                    if($row["approve"]==0)
                                                        echo "Un-approved";
                                                    elseif($row["approve"]==1)
                                                        echo "Pending";
                                                    elseif($row["approve"]==2)
                                                        echo "Confirmed";
                                                    elseif($row["approve"]==3)
                                                        echo "Cancelled";
                                                    ?>
                                                </td>
                                                <td style="text-align: center;"><img src="images/files.png" title="See Payments" onClick="window.location.href='booking_payments.php?id=<?php echo $row['booking_id']?>'" style="cursor:pointer;"></td>
                                                <td align="center">
                                                    <?php
                                                    if($_SESSION['IS_ADMIN']==1)
                                                    {
                                                        if($row["approve"]==0)
                                                        {
                                                            ?>

                                                            <img align="absmiddle" src="images/app.jpg" title="Approve Booking" onClick="window.location.href='booking_listing.php?id=<?php echo $row['booking_id']?>&act=approve<?php echo $qstringall?>'" style="cursor:pointer;">&nbsp;&nbsp;

                                                            <img align="absmiddle" src="images/dec.jpg" title="Cancel Booking" onClick="window.location.href='booking_listing.php?id=<?php echo $row['booking_id']?>&act=cancel<?php echo $qstringall?>'" style="cursor:pointer;">&nbsp;&nbsp;

                                                            <?php
                                                        }
                                                        elseif($row["approve"]==1 && $row["paid"]>0)
                                                        {
                                                            ?>
                                                            <img align="absmiddle" src="images/confirm.jpg" title="Confirm Booking" onClick="window.location.href='booking_listing.php?id=<?php echo $row['booking_id']?>&act=confirm<?php echo $qstringall?>'" style="cursor:pointer;">&nbsp;&nbsp;
                                                            <?php
                                                        }
                                                        elseif($row["approve"]==3)
                                                        {
                                                            ?>
                                                            <img align="absmiddle" src="images/app.jpg" title="Approve Booking" onClick="window.location.href='booking_listing.php?id=<?php echo $row['booking_id']?>&act=approve<?php echo $qstringall?>'" style="cursor:pointer;">&nbsp;&nbsp;
                                                            <?php
                                                        }
                                                        ?>
                                                        <a id="deletelink" href="booking_listing.php?id=<?php echo $row['booking_id']?>&act=delete<?php echo $qstringall?>" onClick="return confirmPost('<?php echo $row['booking_id']?>','<?php echo $qstringall?>')">
                                                            <img align="absmiddle" src="images/delete.png" title="Delete Booking"  style="cursor:pointer;">
                                                        </a>
                                                        <?php
                                                    }
                                                    else
                                                        echo "N/A";
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <tr>
                                            <td align="center" colspan="12" height="100" valign="middle" class="error" >
                                                <b>No Booking Found!</b> </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <br />
                    <table width="99%">
                        <tr align="center">
                            <td width="57%" height="10" align="right" valign="bottom">
                                <?php
                                if($_SESSION['IS_ADMIN']==1)
                                    echo '<input name="submit" type="submit" class="button1" value="Submit" />';
                                ?>
                            </td>
                            <td width="43%" height="10" colspan="3" align="right" valign="bottom">
                                <?php pagination("booking_listing.php",$querystring,$totalPages,$page); ?>
                            </td>
                        </tr>
                    </table>
                </form>


<?php include "include/admin_footer.php";?>