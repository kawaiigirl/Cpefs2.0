<?php
include "include/base_includes.php";
include("include/view/layout.php");

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
        $html .= '<select name="decimalAmount'.$booking['booking_id'].'" id="decimalAmount'.$booking['booking_id'].'" style="height:18px;">';
            if ($balance>0 && $booking['paid']==0)
            {
                if($booking['nights']==3)
                {
                }
                elseif($booking['nights']==14)
                {
                    $html .= '<option value="select">Select:</option>';
                    $html .= '<option value="200">Deposit: $200</option>';
                }
                else
                {
                    $html .= '<option value="select">Select:</option>';
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

foreach($bookings as &$booking)
{
    $booking['booking_status'] = getBookingStatus($booking);

    if ($booking["rate"] == $booking["paid"])
        $booking['balance_paid']="Yes";
    else
        $booking['balance_paid']="No";

    if ($booking['due_date'] == '0000-00-00' || $booking['due_date'] == '')
        $booking['date_due']="N.A.";
    else
        $booking['date_due']= date("d M Y", strtotime($booking['due_date']));

    if($booking["rate"]==$booking["paid"])
        $booking['total_due']="Nil";
    else
        $booking['total_due']= "$".number_format(($booking["rate"]-$booking["paid"]),2);

    $booking['payment_amount'] = GetPaymentAmount($booking);

}

require "include/view/my_bookings_view.php";