<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<div class="row"><h1 class="header">My Bookings</h1>
    <div class="card">
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td width="33%"><a href="my_bookings.php"><< Back to My Bookings</a></td>
                <td width="33%"></td>
                <td width="33%"></td>
            </tr>
            <tr>
                <td class="body_head1"></td>
                <td align="center" class="body_bg1">
                    <strong>Payments Made for Booking ID <?php echo $_GET["id"]; ?></strong></td>
                <td class="body_head2">&nbsp;</td>
            </tr>
        </table>
        <table width="100%" border="1" cellpadding="7" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
            <tr>
                <td align="center" class="heading"><strong>Member</strong></td>
                <td align="center" class="heading"><strong>Unit</strong></td>
                <td align="center" class="heading"><strong>Check In Date</strong></td>
                <td align="center" class="heading"><strong>Rate</strong></td>
                <td align="center" class="heading"><strong>Paid</strong></td>
            </tr>
            <tr>
                <td align="center" class="bgc2"><?php echo show_text($row["member_name"]); ?></td>
                <td align="center" class="bgc2"><?php echo show_text($row["unit_name"]); ?></td>
                <td align="center" class="bgc2"><?php echo show_text($row["check_in_date"]); ?></td>
                <td align="center" class="bgc2"><?php echo show_text($row["rate"]); ?></td>
                <td align="center" class="bgc2"><?php echo show_text($row["paid"]); ?></td>
            </tr>
        </table>
        <table width="100%" border="1" cellpadding="7" cellspacing="0" bordercolor="#CCD5E6" style="border-collapse :collapse" align="center">
            <tr>
                <td width="7%" align="center" class="heading" style="background-color:#dfdfdf"><strong>Date</strong>
                </td>
                <td width="7%" align="center" class="heading" style="background-color:#dfdfdf"><strong>Time</strong>
                </td>
                <td width="5%" align="center" class="heading" style="background-color:#dfdfdf">
                    <strong>Payment Method</strong></td>
                <td width="7%" align="center" class="heading" style="background-color:#dfdfdf"><strong>Amount</strong>
                </td>
            </tr>
            <?php
            if ($rows > 0)
            {
                $res = $dbc->getResult($qry, __LINE__, __FILE__, array("i", & $_GET['id']));
                $color = "";
                while ($row = mysqli_fetch_array($res))
                {
                    if ($color == "bgc2")
                        $color = "bgc";
                    else
                        $color = "bgc2";
                    ?>
                    <tr class="<?php echo $color ?>">
                        <td align="center" style="padding-left:2px;">
                            <?php echo date("d M Y", strtotime($row["date"])); ?>
                        </td>
                        <td align="center" style="padding-left:2px;">
                            <?php echo date("h:ia", strtotime($row["date"])); ?>
                        </td>
                        <td align="center" style="padding-left:2px;">
                            <?php echo show_text($row["payment_method"]) ?>
                        </td>
                        <td align="center" style="padding-left:2px;">
                            <?php echo "$" . $row["amount"] ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            else
            {
                ?>
                <tr>
                    <td align="center" colspan="12" height="100" valign="middle" class="error">
                        <b>No Payment Records!</b>
                    </td>
                </tr>
                <?php
            }
            ?>

        </table>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>