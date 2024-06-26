<?php //booking functions
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


function DisplayBooking($booking, $isPast = false): void
{
    $pastStyle = $pastBackgroundColor = "";
    if($isPast)
    {
        $pastStyle = "style='color:#484848'";
        $pastBackgroundColor =  " background-color: gray";
    }
    ?>
    <div class="card clearfix" <?=$pastStyle?>>
        <h2 class="header" style="text-align: left;<?=$pastBackgroundColor?>"><?=$booking['unit_name']?></h2>
            <div style="display: flex;flex-direction: row;flex-flow: row wrap; ">
                <div class="leftInner">
                    <div class="row"><span class="leftData bookingDetailsLabel"><strong>Invoice Number:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['invoice_number']?></span></div>
                    <div class="row"  style="background-color: #e0e0e0"><span class="leftData bookingDetailsLabel"><strong>Booking Date:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['booking_date']?></span></div>
                    <div class="row" ><span class="leftData bookingDetailsLabel"><strong>Check-in Date:</strong></span><span class="rightData bookingDetailsLabel"><?=date("d M Y",strtotime($booking['check_in_date']));?></span></div>
                    <div class="row"  style="background-color: #e0e0e0"><span class="leftData bookingDetailsLabel"><strong>Check-Out Date:</strong></span><span class="rightData bookingDetailsLabel"><?=date("d M Y",strtotime($booking['check_out_date']));?></span></div>
                    <div class="row" ><span class="leftData bookingDetailsLabel"><strong>Nights:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['nights'];?></span></div>
                   <div class="row" style="background-color: #e0e0e0"><span class="leftData bookingDetailsLabel"><strong>Deposit Paid:</strong></span><span class="rightData bookingDetailsLabel"><?="$".$booking["paid"]?></span></div>
                    </div>
                <div class=" rightInner">
                    <div  class="row"><span class="leftData bookingDetailsLabel"> <strong>Balance Paid:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['balance_paid']?></span></div>
                    <div  class="row" style="background-color: #e0e0e0"><span class="leftData bookingDetailsLabel"><strong>Balance Due On:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['date_due']?></span></div>
                    <div class="row" ><span class="leftData bookingDetailsLabel"><strong>Total Due:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['total_due']?></span></div>
                    <div  class="row" style="background-color: #e0e0e0"><span class="leftData bookingDetailsLabel"><strong>Payments Made:</strong></span>
                        <span class="rightData bookingDetailsLabel tableButtonCell"><a class="nav-link tableLink book-now" href="booking_payments.php?id=<?=$booking['booking_id']?>"> See Payments</a>
                                        </span></div>
                    <div class="row" ><span class="leftData bookingDetailsLabel"><strong>Booking Status:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['booking_status']?></span></div>
                </div>
            </div>
            <?php
            if(!$isPast&&(($booking['rate']>$booking['paid']) &&($booking["approve"]!=0)&&($booking["approve"]!=3)))
            {
                ?>

                <div style="display:flex;flex-direction: row;flex-flow: row wrap;padding:20px;float:left;position:relative;background-color: #ce8383;width: 100%;justify-content: space-around">
                <div class="leftInner paymentSelect" style=" padding-left: 0;">
                    <div class="row" > <?=$booking['payment_amount']?></div>
                </div>
                <div class="rightInner" style="padding: 0;">
                    <?php
                     ?>
                    <div class="row"><input style="margin-top: 6px;" type="submit" value="Pay Now" onClick="populateForm(<?=$booking['booking_id']?>)"></div>
                        <?php
                     ?>
                </div></div>

                    <?php
                }
                    ?>
        </div>

    <?php
}
function GetPaymentAmount($booking): string
{
    $html="";
    $balance = $booking['rate']-$booking['paid'];
    if(($booking['rate']>$booking['paid']) &&($booking["approve"]!=0)&&($booking["approve"]!=3))
    {
        $html .= '<span class="leftData bookingDetailsLabel"><strong>Payment Amount:</strong></span><select class="bookingDetailsLabel" style="padding:12px 20px;" name="decimalAmount'.$booking['booking_id'].'" id="decimalAmount'.$booking['booking_id'].'">';
        if ($balance>0 && $booking['paid']==0)
        {
            if($booking['nights']==3)
            {
            }
            elseif($booking['nights']==14)
            {
                $html .= '<option value="200">Deposit: $200</option>';
            }
            else
            {
                $html .= '<option value="100">Deposit: $100</option>';
            }
            $html .= '<option value="'.$balance.'">Full amount: $'.$balance.'</option>';
        }
        if ($balance>0 && $booking['paid']>0)
        {
            $html .= '<option value="'.$balance.'">Full amount: $'.$balance.'</option>';
        }
        $html .= '</select><br/><label for="decimalAmount'.$booking['booking_id'].'" id="error_decimalAmount'.$booking['booking_id'].'" name="error_decimalAmount" style="color:red;display:none;">You must select an option</label>';
        $html .= '<input type="hidden" id="invoice'.$booking['booking_id'].'" value="'.$booking['invoice_number'].'">';
    }
    return $html;
}
function getBookingStatus(array $booking):string
{
    return match ($booking["approve"])
    {
        1 => "Pending",
        2 => "Confirmed",
        3 => "Cancelled",
        default => "Un-approved",
    };
}

function GetBookings($isPast = false): array
{
    global $dbc;
    $sql="Select cpefs_booking.*,cpefs_units.unit_name From cpefs_booking
			  Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id
			  Where member_id=:memberID";
    if($isPast)
    {
        $sql.=" AND cpefs_booking.check_in_date + interval cpefs_booking.nights day < NOW()";
    }
    else
    {
        $sql.=" AND cpefs_booking.check_in_date + interval cpefs_booking.nights day > NOW()";
    }
    // $sql.=" Order By cpefs_booking.check_in_date";
    return $dbc->getArrayResult($sql,__LINE__,__FILE__,array("memberID"=>$_SESSION['member_id']));
}
function CreateDisplayBookingArray($bookings): array
{
    $displayBookings = array();

    foreach($bookings as $booking)
    {
        $displayBooking = array();
        $displayBooking['booking_status'] = getBookingStatus($booking);
        $displayBooking['payment_amount'] = GetPaymentAmount($booking);
        $displayBooking['unit_name'] = $booking['unit_name'];
        $displayBooking['invoice_number'] = $booking['invoice_number'];
        $displayBooking['rate'] = $booking['rate'];
        $displayBooking['paid'] = $booking['paid'];
        $displayBooking['approve'] = $booking['approve'];
        $displayBooking['booking_id'] = $booking['booking_id'];
        $displayBooking['check_in_date'] = $booking['check_in_date'];
        $displayBooking['check_out_date'] = GetCheckOutDate($booking['check_in_date'], $booking['nights']) ;
        $displayBooking['nights'] = $booking['nights'];
        $displayBooking['booking_date'] = date("d M Y", strtotime($booking['booking_date']));;

        if ($booking["rate"] == $booking["paid"])
            $displayBooking['balance_paid']="Yes";
        else
            $displayBooking['balance_paid']="No";

        if ($booking['due_date'] == '0000-00-00' || $booking['due_date'] == '')
            $displayBooking['date_due']="N.A.";
        else
            $displayBooking['date_due']= date("d M Y", strtotime($booking['due_date']));

        if($booking["rate"]==$booking["paid"])
            $displayBooking['total_due']="Nil";
        else
            $displayBooking['total_due']= "$".number_format(($booking["rate"]-$booking["paid"]),2);

        $displayBookings[] = $displayBooking;
    }
    return $displayBookings;
}

function makePayment($bookingid,$paymentmethod,$amount,$comment): void
{
    global $dbc;
	$_amount = str_replace(",","",$amount);
	$_amount = str_replace("$","",$_amount);
	
	//updates current balance
    $dbc->update("Update cpefs_booking Set paid= paid + ? Where booking_id=?",__LINE__,__FILE__,array("di",&$_amount,&$bookingid));
	
	//inserts payment record
	$qry = "INSERT into cpefs_payments Set booking_id = ? , date = now(), amount= ?, payment_method = ?, comment=?";
	$parameters = array("idss",&$bookingid,&$_amount,&$paymentmethod,& $comment);
	$dbc->insert($qry,__LINE__,__FILE__,$parameters);
}
function deleteBooking($bookingId,$sendMail,$page): string
{
    global $dbc;
	$ssql="Select booking_id FROM cpefs_booking Where booking_id=?";
	$data=$dbc->getSingleData($ssql,__LINE__,__FILE__,array("i",& $bookingId));
    $msg ="";
	if($data != '')
	{
		$dbc->insert("INSERT INTO cpefs_deleted_booking SELECT * FROM cpefs_booking WHERE booking_id = ? ON DUPLICATE KEY UPDATE booking_id = ?",__LINE__,__FILE__,array("ii",&$bookingId,&$bookingId));
		$check = $dbc->getSingleData("Select booking_id From cpefs_deleted_booking where booking_id = ?",__LINE__,__FILE__,array("i",&$bookingId));
		if ($check)
		{
			if($sendMail == 'true')
			{
				$ssql="Select cpefs_booking.*,cpefs_members.*,cpefs_units.unit_name,cpefs_units.unit_location,cpefs_units.manager_email From cpefs_booking 
				   Inner Join cpefs_members On cpefs_members.member_id=cpefs_booking.member_id 
				   Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id Where booking_id=?";
				   
				$data=$dbc->getSingleRow($ssql,__LINE__,__FILE__,array("i",&$bookingId));
				
				$msg="Booking Successfully Deleted";

				$member_name=getFirstName($data['name']);
				$cdate=date("d M Y",strtotime($data['check_in_date']));
				$edate=getEndDate($data['check_in_date'],$data['nights']);
				$location = explode(",", $data['unit_location']);
				$location = $location[0];

				/* Send unit Manager an email if a deposit has been made*/
				if($data['paid']>0 && $data['approve'] ==2){
					
					$toAddress = $data['manager_email'];
					$subject = "CPEFS Unit Booking Cancellation";
					$body    = "Hi,<br><br>We have received a booking cancellation for $data[name], unit: $data[unit_name], $location, for $data[nights] nights from $cdate to $edate. <br>
									<br><br>Regards,<br>CPEFS";
					$altBody = "Hi, 
								We have received a booking cancellation for $data[name], unit: $data[unit_name], $location, for $data[nights] nights from $cdate to $edate.  
								Regards, CPEFS";
					$type = "cancel";
					$recipient = "manager";
					$msg .= sendEmail($bookingId,$toAddress,$subject,$body,$altBody,$page,$type,$recipient);
				}
				/* Send Member an Email */
				$toAddress = $data['member_email'];
				$subject = "CPEFS Unit Booking Cancellation";
				$body    = "Hi $member_name<br><br>
							Your booking has been cancelled for the unit $data[unit_name], $location, from $cdate to $edate. If you have any questions, please contact us at <a href='mailto:admin@cpefs.com.au'>admin@cpefs.com.au</a><br><br>
							Regards,<br>
							CPEFS";
				$altBody = "Hi $member_name, 
							Your booking has been cancelled for the unit $data[unit_name], $location, from $cdate to $edate.  
							If you have any questions, please contact us at admin@cpefs.com.au.	
							Regards, CPEFS";
				$type = "cancel";
				$recipient = "member";
				$msg .= sendEmail($bookingId,$toAddress,$subject,$body,$altBody,$page,$type,$recipient);
				$msg.= "<br/>Emails Sent";
			}
			else
			{
				$msg.= "<br/>No Emails Sent";
			}
			$dbc->delete("Delete From cpefs_booking where booking_id = ?",__LINE__,__FILE__,array("i",&$bookingId));
		}
		else
		$msg="Booking could not be deleted";
	}
	else{
		$msg = "Booking with id $bookingId has already been deleted.";
	}
	return $msg;
}
function approveBooking($bookingId,$data,$page): string
{
    global $dbc;
	$dbc->update("Update cpefs_booking Set approve=1 where booking_id = ?",__LINE__,__FILE__,array("i",&$bookingId));
	$msg="Booking Successfully Approved";
	
	$member_name=getFirstName($data['name']);
	include "../admin/include/generatePDF.php";
	
	/* Send Member an Email */
	$toAddress =$data['member_email'];
	$subject = "CPEFS Unit Booking - Approved";
	$body="Hi $member_name,<br><br>Your booking has been approved and now your deposit is required. Full details of amounts due are shown on the attached invoice.<br><br>
			
			You can pay your deposit immediately by direct deposit <b>(preferred method)</b> or credit card by:<br><br>

			Direct deposit - CPEFS bank details are<br>
			Account name: Castlemaine Perkins Employee Friendly Society<br>
			BSB: 064123<br>
			Account number: 10012729<br><br>
			<b>IMPORTANT Please use your invoice number as the reference so that we can match your payment with your booking</b><br><br>

			Credit card - login to your CPEFS account at www.cpefs.com.au and click My Bookings<br><br>

			Alternate payment methods include<br>
			Telephone<br>
			BPay<br>
			Post Billpay<br>
			In person at Heritage Building Society<br>
			BillEXPRESS<br><br>

			Please reply to this email if you would like to use any of the alternate payment methods.<br><br>

			If you have any questions, please contact us at <a href='mailto:admin@cpefs.com.au'>admin@cpefs.com.au</a><br><br>

			Enjoy your holiday<br>CPEFS";
	$altBody = "Hi $member_name, 
			Your booking has been approved and now your deposit is required. Full details of amounts due are shown on the attached invoice.  
			
			You can pay your deposit immediately by direct deposit (preferred method)  or credit card by:  

			Direct deposit - CPEFS bank details are  --
			Account name: Castlemaine Perkins Employee Friendly Society. 
			BSB: 064123.
			Account number: 10012729.  
			 IMPORTANT Please use your invoice number as the reference so that we can match your payment with your booking. 

			Credit card - login to your CPEFS account at www.cpefs.com.au and click My Bookings.  

			Alternate payment methods include:- 
			Telephone,
			BPay, 
			Post Billpay, 
			In person at Heritage Building Society, 
			BillEXPRESS. 

			Please reply to this email if you would like to use any of the alternate payment methods.  

			If you have any questions, please contact us at admin@cpefs.com.au 

			Enjoy your holiday, CPEFS";

	$type = "approved";
	$recipient = "member";
	$attachment = "pdf/invoice.pdf";
	$attachmentName = "invoice.pdf";
	
	$msg .= sendEmail($bookingId,$toAddress,$subject,$body,$altBody,$page,$type,$recipient,$attachment,$attachmentName);
	return $msg;
}
function confirmBooking($bookingId,$data,$page): string
{
    global $dbc;
    $dbc->update("Update cpefs_booking Set approve=2,confirm_date=now() where booking_id = ?", __LINE__, __FILE__, array("i",&$bookingId));

	$location = explode(",", $data['unit_location']);
	$location = $location[0];

	$msg="Booking Successfully Confirmed";
	$member_name=getFirstName($data['name']);
	$cdate=date("d M Y",strtotime($data['check_in_date']));
	$edate=getEndDate($data['check_in_date'],$data['nights']);

	/* Send Member Confirmation Email */
	$toAddress =$data['member_email'];
	$subject = "CPEFS Unit Booking - Confirmed";
	$body="Hi $member_name<br><br>Your deposit has been received and your unit booking at 

			$data[unit_name] from $cdate to $edate has been confirmed. Please remember that the balance is due 30 days prior to check-in.<br> 
			If you will be arriving out of business hours or at a later date then booked, please contact the unit manager at $data[manager_email] to arrange a late key collection. <br><br> 

			If you have any questions, please contact us at <a href='mailto:admin@cpefs.com.au'>admin@cpefs.com.au</a><br><br>

			Enjoy your holiday<br>CPEFS";
	$altBody = "Hi $member_name, Your deposit has been received and your unit booking at 

			$data[unit_name] from $cdate to $edate has been confirmed. Please remember that the balance is due 30 days prior to check-in. 
			If you will be arriving out of business hours or at a later date then booked, please contact the unit manager at $data[manager_email] to arrange a late key collection.  

			If you have any questions, please contact us at admin@cpefs.com.au  

			Enjoy your holiday, CPEFS";
	$type = "confirm deposit";
	$recipient = "member";
			
	$msg.= sendEmail($bookingId,$toAddress,$subject,$body,$altBody,$page,$type,$recipient);
	$msg .= "<br>";
	/* Send Manager Confirmation Email */
	$manager_toAddress = $data['manager_email'];		
	$manager_subject = "CPEFS Unit Booking - Confirmed";
	$manager_body = "Hi,<br><br>We have received the deposit and confirm that $data[name] has booked the unit: $data[unit_name], $location, for $data[nights] nights from $cdate to $edate. <br>
					$data[name]'s contact telephone number is $data[member_telephone].<br>
	   
					<br><br>Regards,<br>CPEFS";	
	$manager_altBody = "Hi, We have received the deposit and confirm that $data[name] has booked the unit: $data[unit_name], $location, for $data[nights] nights from $cdate to $edate.  
					$data[name]'s contact telephone number is $data[member_telephone].  
	   
					  Regards, CPEFS";	
	$manager_recipient = "manager";			
	$msg.= sendEmail($bookingId,$manager_toAddress,$manager_subject,$manager_body,$manager_altBody,$page,$type,$manager_recipient);
	return $msg;
}

function cancelBooking($bookingId,$data,$page): string
{
    global $dbc;
	$dbc->update("Update cpefs_booking Set approve=3 where booking_id = ?",__LINE__,__FILE__,array("i",&$bookingId));

	$msg="Booking Successfully Cancelled";
	$msg .= "<br>";
	$cdate=date("d M Y",strtotime($data['check_in_date']));

	/* Send Member Cancellation Email */
	$toAddress = $data['member_email'];
	$subject = "CPEFS Unit Booking - Denied";
	$body="Hi $data[name]<br><br>Your booking at $data[unit_name] from $cdate has been denied. <br>

			Please contact <a href='mailto:admin@cpefs.com.au'>admin@cpefs.com.au</a> if you would like to discuss this decision.<br><br>

			Regards,<br>CPEFS";
	$altBody ="Hi $data[name], Your booking at $data[unit_name] from $cdate has been denied.  

			Please contact admin@cpefs.com.au if you would like to discuss this decision.  

			Regards, CPEFS";

	$type = "denied";
	$recipient = "member";

	$msg.=  sendEmail($bookingId,$toAddress,$subject,$body,$altBody,$page,$type,$recipient);
	
	return $msg;
}
function sendFullPaymentBookingEmail($bookingId,$data,$page): string
{
	$location = explode(",", $data['unit_location']);
	$location = $location[0];
	
	$member_name = getFirstName($data['name']);
	$cdate = date("d M Y",strtotime($data['check_in_date']));
	$edate = getEndDate($data['check_in_date'],$data['nights']);
	$body = "Hi,<br><br>We have received full payment and confirm that $data[name] has booked the unit: $data[unit_name], $location, for $data[nights] nights from $cdate to $edate. <br>
				   $data[name]'s contact telephone number is $data[member_telephone].<br>
				   
				   <br><br>Regards,<br>CPEFS";
	$altBody = "Hi, We have received full payment and confirm that $data[name] has booked the unit: $data[unit_name], $location, for $data[nights] nights from $cdate to $edate.  
		   $data[name]'s contact telephone number is $data[member_telephone].  
			 Regards, CPEFS";	
	$type = "full payment";
	$subject =  "CPEFS Unit Booking - Payment Received";
	$msg = sendEmail($bookingId,$data['manager_email'],$subject,$body,$altBody,$page,$type,"manager");
	$msg .= "<br>";
	$member_body = "Hi $member_name,<br><br>We have received full payment and confirm that $data[name] has booked the unit: $data[unit_name], $location, for $data[nights] nights from $cdate to $edate. <br>
				  If you have any questions, please contact us at <a href='mailto:admin@cpefs.com.au'>admin@cpefs.com.au</a><br><br>
				   
				   <br><br>Enjoy your holiday, CPEFS";
	$member_altBody = "Hi $member_name, We have received full payment and confirm that $data[name] has booked the unit: $data[unit_name], $location, for $data[nights] nights from $cdate to $edate.  
						If you have any questions, please contact us at admin@cpefs.com.au . 
						Enjoy your holiday, CPEFS";
	
	
	$msg.= sendEmail($bookingId,$data['member_email'],$subject,$member_body,$member_altBody,$page,$type,"member");
	return $msg;
}
$debugmsg = '';
function sendEmail($bookingId,$toAddress,$subject,$body,$altBody,$page,$type,$recipient,$attachment = false,$attachmentName=""): string
{
    global $dbc;
	ini_set('max_execution_time', 200);

	$admin_email = get_admin_send_email_id();
	$mail_debug = 2;
    $GLOBALS['debugmsg']  = '';
	
	$msg = "<br/>";
	$mail = new PHPMailer();

    $mail->SMTPDebug = $mail_debug;
    $mail->Debugoutput = function($str, $level) { $GLOBALS['debugmsg'] .= " debug level $level; message: $str";};
    $mail->isSMTP();                                     // Set mailer to use SMTP
    $mail->Host = MAIL_HOST;  			 // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = MAIL_USERNAME;                 // SMTP username
    $mail->Password = MAIL_PASSWORD;                           // SMTP password
    $mail->SMTPSecure = MAIL_SECURE;                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = MAIL_PORT;                                    // TCP port to connect to
    $mail->Timeout = 30;
    $mail->From = MAIL_FROM;
    $mail->FromName = MAIL_FROM_NAME;

    if(!$mail->addAddress($toAddress))    //$toAddress Add a recipient
        return 'Email Address is invalid';
    $mail->AddCC($admin_email);

    $mail->isHTML();                                  // Set email format to HTML

    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = $altBody;

    //Add some extra reporting info to alt body
    $databaseLog = "to:".$toAddress. " cc:".  $admin_email . " " . $altBody . " DEBUG: ". $GLOBALS['debugmsg'];

    if($attachment)
    {
        $databaseLog .= "Has Attachment";
        if(!$mail->addAttachment($attachment, $attachmentName))
            return "Attachment Failed";
            // Optional name
    }

    $qry = "INSERT INTO cpefs_emails SET booking_id = :booking_id,recipient=:recipient, date=now(),page = :page,type=:type,content = :content";
    $parameters = array('booking_id'=>$bookingId,'recipient'=>$recipient,'page'=>$page,'type'=>$type,'content'=>$databaseLog);
    if(LOCALHOST)
    {
        $msg.= $mail->Body;
        $qry.= ", sent='false',error_message ='localhost'";
    }
    else
    {
        if(!$mail->send())
        {
            $msg.= "$type message could not be sent to ".$recipient;
            $error_info=$mail->ErrorInfo;
            $msg.= " Mailer Error: " . $error_info;
            $qry .= ", sent='false',error_message =:errorInfo";
            $parameters['errorInfo'] = $error_info;
        }
        else
        {
            $msg.= "$type message has been sent to ".$recipient;
            $qry .= ", sent='true'";
        }
    }
    $dbc->insertPDO($qry,__LINE__,__FILE__,$parameters);
    return $msg;
}