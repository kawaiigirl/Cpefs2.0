<?php
$toshow = 25; //overwriting admin settings;
$start = $variables['start'];
$totalPages = $variables['totalPages'];
$rows = $variables['rows'];
$qryParamsRefs = $variables['paramsRefs'];



/*************************/

$qrystringord .= "&page=$page";

$qstringall .= "&page=$page";
?>
<script language="javascript" src="scripts/cal2.js">
    /*

    Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)

    Script featured on/available at http://www.dynamicdrive.com/

    This notice must stay intact for use

    */
</script>

<script language="javascript" src="scripts/cal_conf2.js"></script>

<script LANGUAGE="JavaScript">

    function confirmPost(id,page)
    {
        let agree = confirm("Are you sure you want to delete?");
        if (agree)
        {
            let yes=confirm("Do you want to send a notification email?");
            if(yes)
            {
                window.location.href = 'booking_listing.php?id='+ id + page +'&act=delete&email=true';

                return false ;
            }
            else
            {
                window.location.href = 'booking_listing.php?id='+ id + page +'&act=delete&email=false';
                return false ;
            }

        }
        else
            return false ;
    }

</script>

<table width="98%" height="350" cellpadding="0"  cellspacing="0" border="0">
    <tr>
        <td colspan="3">
            <table width="100%"  cellspacing="0" cellpadding="0">
                <tr>
                    <td class="body_head1">&nbsp;</td>
                    <td align="center" class="body_bg1"><strong>Manage Bookings</strong></td>
                    <td class="body_head2">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
        <td width="99%"  align="center" valign="top">
            <table width="99%">
                <tr>
                    <td width="33%" height="17" valign="top" class="candylink">
                            