<?php
const incPATH = "../inc/";
include "../inc/common.php";
const PAGE_TITLE = "Admin Password Expired";
include "../inc/functions_login.php";
include "include/admin_header_no_menu.php";

?>
<strong><span style='color:red'>Your password has expired.</span><br/></strong><br>
    An email to reset your password has been sent to you.<br/><br>
<span style='font-size: smaller'>Please contact <a href='mailto:admin@cpefs.com.au'>admin@cpefs.com.au</a> if you have any issues resetting your password.</span>



</table>
</td>
</tr>
</table>
</td>

<?php include "include/admin_footer.php"; ?>
