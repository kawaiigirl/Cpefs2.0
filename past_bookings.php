<?php
include "include/base_includes.php";
include "include/authenticator.php";
include "include/view/layout.php";
include "include/functions_booking.php";

$pastBookings = GetBookings(true);
$displayPastBookings = CreateDisplayBookingArray($pastBookings);
require "include/view/past_bookings_view.php";
