<?php
include "include/base_includes.php";
include "include/authenticator.php";
include "include/view/layout.php";


function DisplayBooking($booking, $isPast = false): void
{
    $pastStyle = $pastBackgroundColor = "";
    if($isPast)
    {
        $pastStyle = "style='color:#484848'";
        $pastBackgroundColor =  " background-color: gray";
    }
    ?>
    <div class="card" <?=$pastStyle?>>
        <h2 class="header" style="text-align: left;<?=$pastBackgroundColor?>"><?=$booking['unit_name']?></h2>
        <div class="row" style="padding:0;">
            <div class="leftColumn leftInner">
                <div class="row"><span class="leftData bookingDetailsLabel"><strong>Invoice Number:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['invoice_number']?></span></div>
                <div class="row" style="background-color: #e0e0e0"><span class="leftData bookingDetailsLabel"><strong>Check-in Date:</strong></span><span class="rightData bookingDetailsLabel"><?=date("d M Y",strtotime($booking['check_in_date']));?></span></div>
                <div class="row"><span class="leftData bookingDetailsLabel"><strong>Check-Out Date:</strong></span><span class="rightData bookingDetailsLabel"><?=date("d M Y",strtotime($booking['check_out_date']));?></span></div>
                <div class="row" style="background-color: #e0e0e0"><span class="leftData bookingDetailsLabel"><strong>Nights:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['nights'];?></span></div>
                <div class="row"><span class="leftData bookingDetailsLabel"><strong>Deposit Paid:</strong></span><span class="rightData bookingDetailsLabel"><?="$".$booking["paid"]?></span></div>
                </div>
            <div class="rightColumn rightInner">
                <div class="row" style="background-color: #e0e0e0"><span class="leftData bookingDetailsLabel"> <strong>Balance Paid:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['balance_paid']?></span></div>
                <div class="row"><span class="leftData bookingDetailsLabel"><strong>Balance Due On:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['date_due']?></span></div>
                <div class="row" style="background-color: #e0e0e0"><span class="leftData bookingDetailsLabel"><strong>Total Due:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['total_due']?></span></div>
                <div class="row"><span class="leftData bookingDetailsLabel"><strong>Payments Made:</strong></span>
                    <span class="rightData bookingDetailsLabel tableButtonCell"><a class="tableLink book-now" href="booking_payments.php?id=<?=$booking['booking_id']?>"> See Payments</a>
                                    </span></div>
                <div class="row" style="background-color: #e3e3e3"><span class="leftData bookingDetailsLabel"><strong>Booking Status:</strong></span><span class="rightData bookingDetailsLabel"><?=$booking['booking_status']?></span></div>
            </div>
            <div class="row" style="padding:0;">
                <div class="leftColumn" style="width: 45%; padding-left: 0">
                    <?=$booking['payment_amount']?><br>
                </div>
                <div class="rightColumn" style="width: 45%;padding: 0;">
                    <?php
                    if(($booking['rate']>$booking['paid']) &&($booking["approve"]!=0)&&($booking["approve"]!=3))
                    { ?>
                        <input style="margin-top: 6px;" type="submit" value="Pay Now" onClick="populateForm(<?=$booking['booking_id']?>)">
                        <?php
                    } ?>
                </div>
            </div>
        </div>
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
        $sql.=" AND cpefs_booking.check_in_date > NOW()";
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

$currentBookings = GetBookings();
$pastBookings = GetBookings(true);
$displayCurrentBookings = CreateDisplayBookingArray($currentBookings);
$displayPastBookings = CreateDisplayBookingArray($pastBookings);
require "include/view/my_bookings_view.php";