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

$pageTitle = "Manage Bookings";
$pageLink = "booking_listing.php";
include "display_bookings.php";
?>
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
<?php include "include/admin_footer.php";?>