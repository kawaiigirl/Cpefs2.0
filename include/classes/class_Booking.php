<?php
class Booking
{
    //Member details
    private int    $member_id;
    private string $name;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $phone;

    //booking details
    private int    $unit_id;
    private string $check_in_date;
    private int    $nights;
    private float  $rate;
    private float  $paid;
    private string $is_peak;

    public  function __construct(int $member_id,int $unit_id,string $firstname,string $lastname,string $email,string $phone,string $check_in_date,int $nights)
    {
        $this->member_id = $member_id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->name = $firstname." ".$lastname;
        $this->email = $email;
        $this->phone = $phone;

        $this->unit_id =  $unit_id;
        $this->check_in_date = $check_in_date;
        $this->nights = $nights;
        $this->is_peak = '';
    }
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Admin created Booking. Inserts booking into the database, automatically approves booking, then redirects back to booking_listing page with a display message depending on if it was a new booking or an edited booking
     */
    public function addManualBooking($rate,$paid)
    {
        $this->insertBooking("Admin - Manual", $rate,$paid,1);

        header("Location:booking_listing.php?opt=1");
        exit;
    }

    /**
     * creates insert/update query and insert/updates the database
     */
    private function insertBooking(string $creator,$rate='',$paid=0, int $approve = 0)
    {
        global $dbc;

        if($rate=='')
        {
            $this->rate = $this->getRate();
        }
        else
            $this->rate = $rate;

        $duedate = $this->getDueDate();

        $invoice_number = Booking::getInvoiceNumber($this->unit_id,$this->check_in_date);

        if($this->is_peak != 'S' && $this->is_peak != 'P')
            if(Booking::isPeakPeriod($this->check_in_date,$this->nights))
                $this->is_peak = "P";
            else
                $this->is_peak = "S";

        $qry = "member_id=?,unit_id=?,firstname=?,lastname=?,name=?,email=?,phone=?,nights=?,rate=?,check_in_date=?,due_date=?,invoice_number=?,is_peak_period=?";
        $qry .= ",approve=?,creator=?";

        $booking_date = date("Y-m-d H:i:s");
        $dbc->insertPDO("Insert into cpefs_booking Set $qry, booking_date='$booking_date'",__LINE__,__FILE__,array( $this->member_id, $this->unit_id,$this->firstname,$this->lastname,$this->name,$this->email,$this->phone,$this->nights,$this->rate,$this->check_in_date, $duedate, $invoice_number, $this->is_peak,$approve,$creator));

        if($paid > 0)
        {
            $this->paid = $paid;
            makePayment($dbc->getLastInsertID(),'Manual Booking',$this->paid,"");
        }
    }
    public function updateBooking($id,$rate = null)
    {
        global $dbc;



        $qry = "Update cpefs_booking set 
                         member_id=:member_id,
                         unit_id=:unit_id,
                         firstname=:firstname,
                         lastname=:lastname,
                         name=:name,
                         email=:email,
                         phone=:phone,
                         nights=:nights,
                         check_in_date=:check_in_date,
                         rate = :rate
                         ";

        $array = ['member_id'=>$this->member_id,
                  'unit_id'=>$this->unit_id,
                  'firstname'=>$this->firstname,
                  'lastname'=>$this->lastname,
                  'name'=>$this->name,
                  'email'=>$this->email,
                  'phone'=>$this->phone,
                  'nights'=>$this->nights,
                  'check_in_date'=>$this->check_in_date,
                  'booking_id'=>$id,
                  'rate'=>$rate];


        $dbc->updatePDO($qry ."WHERE booking_id =:booking_id",__LINE__,__FILE__,$array);
        header("Location:booking_listing.php?opt=2");
        exit;
    }
    /**
     * Member created booking. After inserting new booking into the database, it sends an email to the admin for approval and returns a display message for the user.
     * @param $name
     * @return string
     */
    public function addUserBooking($name): string
    {
        global $dbc;
        $this->insertBooking("Member");

        /* Send Admin an email informing about this booking request */
        $body = "Hi Administrator,<br><br>A new booking request has been made by $name. Please goto the admin section to see
               the details.<br><br>Regards,<br>CPEFS";
        $altBody = "Hi Administrator, A new booking request has been made by $name. Please goto the admin section to see
               the details.  Regards, CPEFS";

        sendEmail($dbc->getInsertID(), get_admin_receive_email_id(), "CPEFS Unit Booking Request", $body, $altBody, "book-unit", "Booking Request", "Admin");
        return "<span style='color: red'>&nbsp;Thank You! Your booking request has been sent to the administrator.</span>";
    }

    /**
     * checks if input is not empty and of correct type
     * @return array An array ['focusOnError'=>'unit_id','errMsg'=>['unit_id'=>'[Please select Unit]']]
     * where $errMsg is an array with an error for each input
     */
    public static function validateInput($member_id, $unit_id, $firstname, $lastname, $email, $phone, $check_in_date, $nights): array
    {
        $validator=new Validator;
        $focusOnError="";
        $errMsg = array('member_id'=>'','unit_id'=>'','firstname'=>'','lastname'=>'','email'=>'','phone'=>'','check_in_date'=>'','nights'=>'');
        if(!$validator->General($member_id) || !$validator->Number($member_id))
        {
            $focusOnError='member_id';
            $errMsg['member_id'] = ' [Please select Member]';
        }
        if(!$validator->General($unit_id) || !$validator->Number($unit_id))
        {
            if($focusOnError=="")
                $focusOnError='unit_id';
            $errMsg['unit_id'] =' [Please select Unit]';
        }
        if(!$validator->General($firstname,'[Please enter firstname]'))
        {
            if($focusOnError=="")
                $focusOnError='firstname';
            $errMsg['firstname'] =' [Please enter firstname]';
        }
        if(!$validator->General($lastname,'[Please enter lastname]'))
        {
            if($focusOnError=="")
                $focusOnError='lastname';
            $errMsg['lastname'] =' [Please enter lastname]';
        }
        if(!$validator->General($email,'[Please enter email]'))
        {
            $errMsg['email'] =' [Please enter email]';
            if($focusOnError=="")
                $focusOnError='email';
        }
        elseif(!$validator->Email($email,'[Please enter a valid email]'))
        {
            if($focusOnError=="")
                $focusOnError='email';
            $errMsg['email'] =' [Please enter a valid email]';
        }
        if(!$validator->General($phone))
        {
            if($focusOnError=="")
                $focusOnError='phone';
            $errMsg['phone'] =' [Please specify a phone number]';
        }
        if(!$validator->General($nights))
        {
            if($focusOnError=="")
                $focusOnError='nights';
            $errMsg['nights'] =' [Please specify No. of Nights]';
        }
        elseif(!$validator->Number($nights,'[Please enter a number]'))
        {
            if($focusOnError=="")
                $focusOnError='nights';
            $errMsg['nights'] = ' [Please enter a number]';
        }
        if(!$validator->General($check_in_date))
        {
            if($focusOnError=="")
                $focusOnError='check_in_date';
            $errMsg['check_in_date'] = ' [Please specify Check In Date]';
        }
        return array('focusOnError'=>$focusOnError,'errMsg'=>$errMsg);
    }

    /**
     * Validates a booking - checks check_in_date issues, checks peak period issues
     * @return array
     */
    public function validateBooking(): array
    {
        $errorArray = array();
        $focusOnError = '';
        $msgCheckInDate = $this->validateCheckInDate();
        if($msgCheckInDate != '')
        {
            $errorArray['check_in_date'] = $msgCheckInDate;
            $focusOnError = "check_in_date";
        }
        $msgPeakBooking = $this->validatePeakBooking();
        if($msgPeakBooking !='')
        {
            $errorArray['check_in_date'] = $msgPeakBooking;
            $focusOnError = "check_in_date";
        }
        return array('focusOnError'=>$focusOnError,'errMsg'=>$errorArray);
    }

    /**
     * checks that the check_in_date is - in the future - within correct timeframe for length of booking - starts on the correct day for length of booking - checks if dates are available
     * @return string
     */
    private function validateCheckInDate(): string
    {
        $errMsg = '';
        if(!$this->isInTheFuture())
            $errMsg = "&nbsp;[booking must be for a future period]";
        else
        {
            if(!$this->isWithinTimeframeAllowed())
                switch($this->nights)
                {
                    case 3:
                        $errMsg = "&nbsp;[Weekend Booking can only be done <b>1 month</b> in advance]";
                        break;
                    case 4:
                        $errMsg = "&nbsp;[Weekday Booking can only be done <b>1 month</b> in advance]";
                        break;
                    case 7:
                    case 14:
                    $errMsg = "&nbsp;[Weekly Booking can only be done <b>12 months</b> in advance]";
                        break;
                }
            elseif(!$this->isCorrectDay())
                switch($this->nights)
                {
                    case 3:
                        $errMsg = "&nbsp;[Check-in-date must be a <b>Friday</b>]";
                        break;
                    case 4:
                    case 7:
                    case 14:
                    $errMsg = "&nbsp;[Check-in-date must be a <b>Monday</b>]";
                        break;
                }
            else
            {
                $msg = $this->isAvailable();
                if($msg !='')
                    $errMsg = "<br>".$msg;
            }
        }

        return $errMsg;
    }
    public function isAvailable()
    {
        $availabilityResult = Booking::checkAvailable($this->unit_id, $this->check_in_date, $this->nights);
        if($availabilityResult['errors']==0)
            return '';
        else
            return $availabilityResult['dateList'];
    }
    public static function getAvailabilityDateList($unit_id, $check_in_date, $nights):string
    {
        $availabilityResult = Booking::checkAvailable($unit_id, $check_in_date, $nights);
        return $availabilityResult['dateList'];
    }
    /**
     *
     * @param $unit_id
     * @param $check_in_date
     * @param $nights
     * @return array returns '' if there is no conflict of dates. or a list of dates if there is a conflict
     */
    public static function checkAvailable($unit_id, $check_in_date, $nights): array
    {
        //Checks for conflicting booking from $check_in_date to $nights -1
        global $dbc;
        $dateList = '';
        $errors = 0;

        for($day = 0; $day < $nights; $day++)
        {
            $ssql="Select * from cpefs_booking
            WHERE :check_in_date +interval $day day BETWEEN check_in_date AND (check_in_date + interval (nights -1) day)
			And unit_id=:unit_id And approve<>3";

            /*"Select * from cpefs_booking
										Where TO_DAYS(:check_in_date)+$day-1 >= TO_DAYS(check_in_date)
										AND TO_DAYS(:check_in_date)+$day-1 <= (TO_DAYS(check_in_date)+nights-1)
										And unit_id=:unit_id
										And approve<>3";
            */

            $numberOfRows=$dbc->getNumRowsPDO($ssql, __LINE__, __FILE__, array("check_in_date"=>$check_in_date,"unit_id"=>$unit_id));

            $formated_Date = date('D d/m/y', strtotime($check_in_date. ' + '.$day .'day'));

            if($numberOfRows>0)
            {
                $dateList .= "<span style='color:red'>&nbsp;" . $formated_Date . " - [Not Available]</span><br/>";
                $errors++;
            }
            else
                $dateList.= "<span style='color:#00af00'>&nbsp;" .$formated_Date ." - [Available]</span><br/>";
        }
       return ['errors'=>$errors,'dateList'=>$dateList];
    }
    //Peak check Logic:
    //Check Bookings that start in peak
    //Check bookings that end in peak
    //Check bookings that start before peak starts and ends after the peak ends
    /**
     * checks if all or part of the booking is within a peak period
     * @param $check_in_date
     * @param $nights
     * @return bool
     */
    public static function isPeakPeriod($check_in_date,$nights) :bool
    {
        global $dbc;

        //We don't check the last day, because they checkout in the morning. Thus -1
        $check_out_date = date('Y-m-d', strtotime($check_in_date. ' + '.($nights-1).'day'));

        /* //sql query with variables
            $sqlIsBookingOnPeak="Select peak_period_id From cpefs_peak_periods
                                 where $check_in_date BETWEEN peak_period_start_date and peak_period_end_date
                                 or $check_out_date BETWEEN peak_period_start_date and peak_period_end_date
                                 or ($check_in_date > peak_period_end_date and $check_out_date < peak_period_start_date)";
        */
        $sql = "Select peak_period_id From cpefs_peak_periods
                 where :check_in_date BETWEEN peak_period_start_date and peak_period_end_date
                 or :check_out_date BETWEEN peak_period_start_date and peak_period_end_date
                 or (:check_in_date > peak_period_end_date and :check_out_date < peak_period_start_date)";

        $numRows = $dbc->getNumRowsPDO($sql,__LINE__,__FILE__,array('check_in_date'=>$check_in_date,'check_out_date'=>$check_out_date));

        return $numRows>0;

    }

    /**
     * Generates Invoice Number
     */
    public static function getInvoiceNumber($unit_id,$check_in_date): string
    {
        $formatedDate = date("Y/m/d",strtotime($check_in_date));
        $date1 = explode('/', $formatedDate);
       return $unit_id . $date1[2] . $date1[1] . substr($date1[0], 2, 2);
    }

    /**
     * gets due date of payment
     */
    public function getDueDate()
    {
        /* Calculating the due date if Nights are more than 3 days */
        if($this->nights > 4)
        {
            $d = date("d", strtotime($this->check_in_date));
            $m = date("m", strtotime($this->check_in_date));
            $y = date("Y", strtotime($this->check_in_date));
            return  date("Y-m-d", mktime(0, 0, 0, $m, $d - 35, $y));
        }
        else
            return  date("Y-m-d");
    }

    /**
     * returns rate based on amount of days and whether it is a peak booking or not
     * @return float
     */
    public function getRate():float
    {
        global $dbc;
        /* Finding the rate */

        $amount = $dbc->getSingleRow("Select * From cpefs_units Where unit_id=?", __LINE__, __FILE__, array("i", & $this->unit_id));

        switch($this->nights)
        {
            case 3:
            case 4:
                $rate = $amount['weekend_rate'];
                break;
            case 7:
                if($this->is_peak)
                   $rate = $amount['peak_rate'];
                else
                    $rate = $amount['basic_rate'];
                break;
            case 14:
                if($this->is_peak)
                    $rate = $amount['basic_rate'] + $amount['peak_rate'];
                else
                   $rate = $amount['basic_rate'] *2;
                break;
            default:
            $rate = $amount['basic_rate'];
        }
        return $rate;
    }

    /**
     *  returns true if member has made a peak booking within 6 months backward and forward
     * @return bool
     */
    private function hasAlreadyMadePeakBooking(): bool
    {
        global $dbc;

        $check_out_date = date('Y/m/d', strtotime($this->check_in_date. ' + '.($this->nights-1) .'day'));



        //find booking
        //where booking.check_in_date is Between newBooking.check_in_date-6 months and newBooking.check_out_date + 6 months
        //Or booking.check_out_date is Between newBooking.check_in_date-6 months and newBooking.check_out_date + 6 months
        $sql = "SELECT *
                        FROM cpefs_booking 
                        WHERE nights in (7,14) AND member_id=:member_id And approve in (0,1,2)
                        AND (cpefs_booking.check_in_date 
                            BETWEEN  (:check_in_date - interval 6 month) AND (:check_out_date + interval 6 month)
                            OR 
                             (cpefs_booking.check_in_date + interval (nights -1) day)
                                 BETWEEN (:check_in_date - interval 6 month )AND (:check_out_date + interval 6 month))";

        $res = $dbc->getResultPDO($sql, __LINE__, __FILE__, array('member_id'=>$this->member_id, 'check_in_date'=>$this->check_in_date,'check_out_date'=>$check_out_date));

        while($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            if($this->isPeakPeriod($row['check_in_date'], $row['nights']))
            {
                return true;
            }
        }
       return false;
    }
    private function isInTheFuture() :bool
    {
        return date('Y/m/d')<=$this->check_in_date;
    }
    private function isCorrectDay() :bool
    {
        $day = date("D", strtotime($this->check_in_date));
        switch($this->nights)
        {
            case 3:
                $correctDay ='Fri';
                break;
            case 4:
            case 7:
            case 14:
            default:
                $correctDay = 'Mon';
                break;
        }
        return $correctDay == $day;
    }
    private function isWithinTimeframeAllowed():bool
    {
        switch($this->nights)
        {
            case 3:
            case 4:
                $addTime = '+1 month';
                break;
            case 7:
            case 14:
            default:
                $addTime = '+12 month';
                break;
        }
        $date = new DateTime('now');
        $date->modify($addTime); // or you can use '-90 day' for deduct
        $date = $date->format('Y/m/d');
            return $this->check_in_date < $date;
    }

    /**
     * Check if it is a peak booking only if an error hasn't already been found
     */
    private function validatePeakBooking(): string
    {
        if(Booking::isPeakPeriod($this->check_in_date, $this->nights))    /* It is a peak period */
        {
            //todo::double check this is all correct with testing
            $this->is_peak = 'P';

            if($this->nights < 7) /* If booked for other than 7 days & peak period then not available */
            {
                return "<span style='color:red;'>&nbsp;[Peak Bookings are only available only for 7 days]</span>";
            }
            if($this->nights > 7) //If bookings are 14 days only 7 days can be within peak period.
            {
                //split into 2 sections then check each week if it is peak;
                $second_Week_CheckInDate =date("Y/m/d", strtotime($this->check_in_date . ' + 7 day'));
                $second_Week_Nights = 7;

                if(Booking::isPeakPeriod($this->check_in_date,$second_Week_Nights) && Booking::isPeakPeriod($second_Week_CheckInDate,$second_Week_Nights))
                {
                    return "<span style='color:red;'>&nbsp;[Peak Bookings are only available for 7 days]</span>";
                }

            }
            //All of their bookings in within 6 months (forward and backwards;
            if($this->hasAlreadyMadePeakBooking())
            {
                return "<span class='error' style='color:red'>&nbsp;[Sorry!!! You already have one peak period booking within 6 months]</span>";
            }
        }
        return '';
    }
}