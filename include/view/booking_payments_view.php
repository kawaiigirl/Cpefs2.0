<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>
<style>
    td{

        padding: 5px;
        border: 1px #dfdfdf solid;
        border-spacing: 0;
    }
    th{
        background-color: #dfdfdf;
    }
    table{
        width:100%;text-align: center;border: 1px #dfdfdf;border-collapse:collapse;
    }
</style>
<div class="row"><h1 class="header">My Bookings</h1>
    <div class="singleColumn">
    <div class="card"><a class="nav-link" href="my_bookings.php"><< Back to My Bookings</a></div>
    <div class="card">
       <h2>Payments Made for Booking ID <?=$_GET["id"]; ?></h2>
        <table>
            <tr>
                <th><strong>Member</strong></th>
                <th><strong>Unit</strong></th>
                <th><strong>Check In Date</strong></th>
                <th><strong>Rate</strong></th>
                <th><strong>Paid</strong></th>
            </tr>
            <tr>
                <td><?php echo show_text($row["member_name"]); ?></td>
                <td><?php echo show_text($row["unit_name"]); ?></td>
                <td><?php echo show_text($row["check_in_date"]); ?></td>
                <td><?php echo show_text($row["rate"]); ?></td>
                <td><?php echo show_text($row["paid"]); ?></td>
            </tr>
        </table>
        <table>
            <tr>
                <th><strong>Date</strong>
                </th>
                <th><strong>Time</strong>
                </th>
                <th>
                    <strong>Payment Method</strong></th>
                <th><strong>Amount</strong>
                </th>
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
                        <td>
                            <?php echo date("d M Y", strtotime($row["date"])); ?>
                        </td>
                        <td>
                            <?php echo date("h:ia", strtotime($row["date"])); ?>
                        </td>
                        <td>
                            <?php echo show_text($row["payment_method"]) ?>
                        </td>
                        <td>
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
                    <td colspan="12" height="100" style="vertical-align: center" class="error">
                        <b>No Payment Records!</b>
                    </td>
                </tr>
                <?php
            }
            ?>

        </table>
    </div>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>