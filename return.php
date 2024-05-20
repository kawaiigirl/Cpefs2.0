<?php
include "include/base_includes.php";
include "include/authenticator.php";
include "include/view/layout.php";
include "include/functions_booking.php";
include "include/phpmailer/PHPMailer.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());

if(count($_POST)>0)
{
	if(isset($_POST['response']) && str_contains($_POST['response'], "APPROVED"))
	{
		makePayment($_POST['v1'],'Stratapay',$_POST['v2'],'');
		$bookingId = $_POST['v1'];
		$msg="Thank you! Your payment has been successfully processed.<br/>";


        global $dbc;
		
		$sql="Select cpefs_booking.*,cpefs_members.*,cpefs_units.unit_name,cpefs_units.unit_location, cpefs_units.manager_email From cpefs_booking 
				   Inner Join cpefs_members On cpefs_members.member_id=cpefs_booking.member_id 
				   Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id Where booking_id=?";

		$data=$dbc->getSingleRow($sql,__LINE__,__FILE__,array("i",& $bookingId));
		$member_name=getFirstName($data['name']);
		$location = explode(",", $data['unit_location']);
		$location = $location[0];

               //Start of Auto Confirm Method
		if($data['approve'] == 1)
		{
			//default full amount for < 3
			$requiredPaidAmount = $data['rate'];
			
			if($data['nights'] > 3 && $data['nights'] < 14)
			{
				$requiredPaidAmount = 100;
			}
			
			if($data['nights'] >= 14)
			{
				$requiredPaidAmount = 200;
			}
			
			//Check if correct deposit amount
			if($data['paid'] >= $requiredPaidAmount)
			{
				confirmBooking($bookingId,$data,'stratapay');
				$data['approve'] = 2;
			}
		}
		//End of Auto Confirm Method


		if($data['rate']==$data['paid'] && $data['approve']==2)	// Full payment is done and confirmed
		{
			sendFullPaymentBookingEmail($bookingId,$data,'stratapay');
		}	
	}
	else
	{
		$msg="Error 22913, Your payment has failed.";
	}
}
else
{
		$msg="Error 85422, Please contact admin@cpefs.com.au";
}

?>
<div class="row"><h1 class="header">My Bookings</h1>
    <div class="singleColumn">
        <div class="card">
<strong><?php echo $msg?> </strong>
        </div>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>
