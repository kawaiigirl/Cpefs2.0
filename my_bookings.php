<?php
include "include/base_includes.php";
include "include/authenticator.php";
include "include/view/layout.php";

global $dbc;
$sql="Select cpefs_booking.*,cpefs_units.unit_name From cpefs_booking
			  Inner Join cpefs_units On cpefs_units.unit_id=cpefs_booking.unit_id
			  Where member_id=:memberID Order By booking_date";

$bookings = $dbc->getArrayResult($sql,__LINE__,__FILE__,array("memberID"=>$_SESSION['member_id']));

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
require "include/view/my_bookings_view.php";