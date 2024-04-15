<?php
const incPATH = "../inc/";
include "../inc/common.php";
include "../inc/functions_login.php";
const PAGE_TITLE = "Admin Reset Password";
const TYPE ="admin";
if (isset($_GET["token"]) && isset($_GET["email"]) )
{
    include "include/admin_header_no_menu.php";
    include "../inc/reset_password_body.php";
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