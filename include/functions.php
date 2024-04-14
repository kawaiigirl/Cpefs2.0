<?php
function GetNavLinks($loggedIn=false): array
{
    $navLinks = array(
        array("href" => "index.php", "text" => "Home"),
        array("href" => "contact.php", "text" => "Contact Us"),
        array("href" => "legals.php", "text" => "Legals")
    );
    if($loggedIn)
    {
        $navLinks[] = array("href" => "Units.php", "text" => "Units");
        $navLinks[] = array("href" => "Units_availability.php", "text" => "Unit Availability");
        $navLinks[] = array("href" => "my_bookings.php", "text" => "My Bookings");
        $navLinks[] = array("href" => "make_booking.php", "text" => "Make Booking");
        $navLinks[] = array("href" => "logout.php", "text" => "Logout");
    }
    else
    {
        $navLinks[] = array("href" => "login.php", "text" => "Login");
    }
    return $navLinks;
}
function get_admin_receive_email_id()
{
    global $dbc;
    $qry="select admin_receive_email from cpefs_settings ";
    return $dbc->getSingleData($qry,__LINE__,__FILE__);
}
function get_admin_send_email_id()
{
    global $dbc;
    $qry="select admin_send_email from cpefs_settings ";
    return $dbc->getSingleData($qry,__LINE__,__FILE__);
}
trait errorArrayTrait
{
    public array $errors = array();

    public static function SpanClassError($text): string
    {
        return "<span class='error'> [" . $text . "]</span>";
    }

    public function hasErrors(): bool
    {
        return count($this->errors) != 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

function CronLog($string)
{
    if(LOCALHOST)
        echo "<br>";
    echo "\n " . $string;
}

function Now()
{
    return date("Y-m-d H:i:s");
}

function CurrentDate()
{
    return date("Y-m-d");
}

function IsPostSet($postName): bool
{
    return isset($_POST[$postName]);
}

function IsGetSet($getName): bool
{
    return isset($_GET[$getName]);
}

function IsPostSetTo($postName, $toValue): bool
{
    return IsPostSet($postName) && $_POST[$postName] == $toValue;
}

function IsGetSetTo($getName, $toValue): bool
{
    return IsGetSet($getName) && $_GET[$getName] == $toValue;
}

function IsPostGetSetTo($name, $toValue): bool
{
    if(IsPostSetTo($name, $toValue))
        return true;
    if(IsGetSetTo($name, $toValue))
        return true;
    return false;
}

function DisplayPostOrGet(string $variableName, $default = "")
{
    echo SetFromPostOrGet($variableName, $default);
}

function IsArraySetAndEquals($array, $variableName, $equalString): bool
{
    return Functions::IsArraySetAndNotEmpty($array, $variableName) && $array[$variableName] == $equalString;
}

function IsPostGetSetAndNotEmpty($variableName): bool
{
    if(IsPostSetAndNotEmpty($variableName))
        return true;

    if(IsGetSetAndNotEmpty($variableName))
        return true;

    return false;
}

function DisplayDate($date)
{

    if($date != "")
        return date("d-m-Y", strtotime($date));
    return "";
}

function DisplayDateTime($date)
{

    if($date != "")
        return date("d-m-Y H:i", strtotime($date));
    return "";
}

function DisplayHumanReadableDate($date): string
{
    $time = strtotime($date);
    $string = "";
    if($date != "")
    {
        $string .= date("l", $time);
        $string .= " the ";
        $string .= date("jS", $time);
        $string .= " of ";
        $string .= date('M', $time);

    }
    return $string;
}

function FormatDateYmd($date)
{
    if($date != "")
        return date("Y-m-d", strtotime($date));
    return "";
}

function AddLeadingZero($int): string
{
    if((int)$int <= 9)
    {
        return "0" . $int;
    }
    return $int;
}

function SetFromPostOrGet(string $variableName, $default = "")
{
    if(IsPostSetAndNotEmpty($variableName))
        return htmlspecialchars($_POST[$variableName], ENT_QUOTES);

    if(IsGetSetAndNotEmpty($variableName))
        return htmlspecialchars($_GET[$variableName], ENT_QUOTES);
    return $default;
}

function SetFromPost(string $variableName, $default = "")
{
    if(IsPostSetAndNotEmpty($variableName))
        return htmlspecialchars($_POST[$variableName], ENT_QUOTES);
    return $default;
}

function IsPostSetAndNotEmpty($variableName): bool
{
    return Functions::IsArraySetAndNotEmpty($_POST, $variableName);
}

function IsGetSetAndNotEmpty($variableName): bool
{
    return Functions::IsArraySetAndNotEmpty($_GET, $variableName);
}

function SetFromGet(string $variableName, $default = "")
{
    if(IsGetSetAndNotEmpty($variableName))
        return htmlspecialchars($_GET[$variableName], ENT_QUOTES);
    return $default;
}

function PrintError($errorArray, $errorName)
{
    if(isset($errorArray[$errorName]))
        echo $errorArray[$errorName];
}

function PrintSafeText($text): string
{
    return htmlspecialchars($text, ENT_QUOTES);
}

class Functions
{
    public static function DisplayMsgCard($msg = "")
    {
        if($msg != "")
        {
            echo "<div class='card clearfix'>";
            echo $msg;
            echo "</div>";
        }
    }

    public static function activeMenuPage($currect_page)
    {
        $url_array = explode('/', $_SERVER['REQUEST_URI']);
        $url = end($url_array);
        if($currect_page == $url)
        {
            echo " class='active' "; //class name in css
        }
    }

    public static function consoleLog($output)
    {
        if(LOCALHOST)
        {
            echo "<script>console.log('" . json_encode($output) . "')</script>";
        }
    }

    public static function LogError($msg)
    {
        global $dbc;
        $dbc->LogError($msg);
    }

    public static function consoleLogArray(array $output)
    {
        if(LOCALHOST)
        {
            var_dump($output);
            // echo "<script>console.log($output)</script>";
        }
    }

    public static function prettifyColumn(string $column): string
    {
        $column = str_replace("_", " ", $column);
        return ucfirst($column);
    }

    public static function IsArraySetAndNotEmpty($array, $variableName): bool
    {
        return isset($array[$variableName]) && $array[$variableName] != '';
    }

    public static function IsArraySetAndEquals($array, $variableName, $equalString): bool
    {
        return IsArraySetAndEquals($array, $variableName, $equalString);
    }

    public static function CreateDateTime($date, $time)
    {
        $phpDate = date('Y-m-d', strtotime($date));
        return date('Y-m-d H:i:s', strtotime($phpDate . " " . $time));     //adding the time of check-in for easy date comparing
    }

    /*public static function GetPhpDateFromJavascript($string,$addTime='')
    {
        $phpDate = $string;
        $phpDate = substr($phpDate, 0, strpos($phpDate, '('));
        $phpDate = date('Y-m-d',strtotime($phpDate));
        return date('Y-m-d H:i:s', strtotime($phpDate." ".$addTime));
    }
*/
    public static function SetVariableFromDateOrGet($startDate, $variableName, $dateFormat)
    {
        if($startDate != "" && !isset($_GET[$variableName]))
            return date($dateFormat, strtotime($startDate));
        else
            return SetFromPostOrGet($variableName, date($dateFormat));
    }

    public static function GetCurrentYear()
    {
        return date('Y');

    }

    public static function GetNextYear()
    {
        return date('Y', strtotime(Functions::GetCurrentYear() . " + 1 Year"));
    }

    public static function DisplayStringAsList($features)
    {
        echo "<ul style='list-style: disc;'>";
        $featureArray = explode(",", $features);
        foreach($featureArray as $feature)
        {
            echo "<li>$feature</li>";
        }
        echo "</ul>";
    }

    public static function DisplayEnumSelectOptions(string $field, string $table, $selected = "")
    {
        $enumValues = DBC::get_enum_values($table, $field);
        foreach($enumValues as $enumValue)
        {
            echo "<option value='$enumValue'";
            if($selected == $enumValue)
            {
                echo " SELECTED ";
            }
            echo ">" . Functions::prettifyColumn($enumValue) . "</option>";
        }
    }

    public static function LogException(Exception $e)
    {
        global $dbc;
        $msg = "ExceptionError:: " . $e->getMessage() . " " . $e->getFile() . " " . $e->getLine() . " " . $e->getTraceAsString() . " " . $e->getCode();
        $dbc->LogError($msg);
    }

    public static function LogPHPMailerException(\PHPMailer\PHPMailer\Exception $e)
    {
        global $dbc;
        $msg = "ExceptionError:: " . $e->getMessage() . " " . $e->getFile() . " " . $e->getLine() . " " . $e->getTraceAsString() . " " . $e->getCode();
        $dbc->LogError($msg);
    }

    public static function CheckRecaptcha(array $postArray): bool
    {
        // $ip = $_SERVER['REMOTE_ADDR'];
        // post request to server
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode(SECRET_KEY) . '&response=' . urlencode($postArray['g-recaptcha-response']);
        $response = file_get_contents($url);
        $responseKeys = json_decode($response, true);
        // should return JSON with success as true
        if($responseKeys["success"])
            return true;
        else
            return false;

    }

    public static function PrintRecaptcha($errors)
    {
        PrintError($errors, 'recaptcha');
        echo "<div class='row' style='text-align: center'><div class='g-recaptcha' style='display: inline-block;' data-sitekey='" . SITE_KEY . "'></div></div>";
    }

    //returns colour string
    public static function GetStatusColor($status): string
    {
        switch($status)
        {
            case 'denied':
            case 'cancelled':
            case 'rejected':
            case 'inactive':
            case 'unpaid fees':
            case 'unpaid':
            case 'pending':
                return 'red';
            case 'confirmed':
            case 'cancellation requested':
                return 'orange';
            case 'completed':
            case 'active':
            case 'paid':
            case 'approved':
                return 'green';
            default :
                return 'black';
        }
    }

    public static function AddToLog(string $logString, string $sentFromPage, bool $sendEmail)
    {
        global $dbc;
        $dbc->insert("INSERT INTO log SET log=:log,date= :now", __LINE__, __FILE__, array("log" => $logString, "now" => Now()));
        if($sendEmail)
        {
            EmailTemplates::SendTechSupportLogEntry($logString, $sentFromPage);
        }
    }
}
