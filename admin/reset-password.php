<?php
const incPATH = "../include/";
include "../include/common.php";
include "../include/classes/functions_login.php";
const PAGE_TITLE = "Admin Reset Password";
const TYPE ="admin";
if (isset($_GET["token"]) && isset($_GET["email"]) )
{
    include "include/admin_header_no_menu.php";
    include "../include/reset_password_body.php";
    ?>
    </table>
        </td>
    </tr>
    </table>
    </td>
<?php
include "include/admin_footer.php";
}
?>