<?php
global $pageTitle, $pageLink;
$toshow = 25; //overwriting admin settings;
$start = $variables['start'];
$totalPages = $variables['totalPages'];
$rows = $variables['rows'];
$qryParamsRefs = $variables['paramsRefs'];

/*************************/

$qrystringord .= "&page=$page";

$qstringall .= "&page=$page";

function getBookingListingManageLinks($row, $qryStringAll): void
{
    if ($_SESSION['IS_ADMIN'] == 1)
    {
        if ($row["approve"] == 0)
        {
            ?>

            <img align="absmiddle" src="images/app.jpg" title="Approve Booking" onClick="window.location.href='booking_listing.php?id=<?php echo $row['booking_id'] ?>&act=approve<?php echo $qryStringAll ?>'" style="cursor:pointer;">&nbsp;&nbsp;

            <img align="absmiddle" src="images/dec.jpg" title="Cancel Booking" onClick="window.location.href='booking_listing.php?id=<?php echo $row['booking_id'] ?>&act=cancel<?php echo $qryStringAll ?>'" style="cursor:pointer;">&nbsp;&nbsp;

            <?php
        }
        elseif ($row["approve"] == 1 && $row["paid"] > 0)
        {
            ?>
            <img align="absmiddle" src="images/confirm.jpg" title="Confirm Booking" onClick="window.location.href='booking_listing.php?id=<?php echo $row['booking_id'] ?>&act=confirm<?php echo $qryStringAll ?>'" style="cursor:pointer;">&nbsp;&nbsp;
            <?php
        }
        elseif ($row["approve"] == 3)
        {
            ?>
            <img align="absmiddle" src="images/app.jpg" title="Approve Booking" onClick="window.location.href='booking_listing.php?id=<?php echo $row['booking_id'] ?>&act=approve<?php echo $qryStringAll ?>'" style="cursor:pointer;">&nbsp;&nbsp;
            <?php
        }
        ?>
        <a id="deletelink" href="booking_listing.php?id=<?php echo $row['booking_id'] ?>&act=delete<?php echo $qryStringAll ?>" onClick="return confirmPost('<?php echo $row['booking_id'] ?>','<?php echo $qryStringAll ?>')">
            <img align="absmiddle" src="images/delete.png" title="Delete Booking" style="cursor:pointer;"> </a>
        <?php
    }
    else
        echo "N/A";
}

function getDeletedBookingsManageLinks($row, $qryStringAll): void
{
    echo '<img align="absmiddle" src="images/app.jpg" title="Undo Deletion" onClick="window.location.href=\'deleted_bookings.php?id=' . $row['booking_id'] . '&act=undo_delete' . $qryStringAll . '\'" style="cursor:pointer;">';
}

?>
<script language="javascript" src="scripts/cal2.js">
    /*

    Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)

    Script featured on/available at http://www.dynamicdrive.com/

    This notice must stay intact for use

    */
</script>
<script language="javascript" src="scripts/cal_conf2.js"></script>
<style>
    .searchBar {
        width: 99%;
        height: 30px;
    }

    table .bookingsTable {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #CCD5E6;
        text-align: center;
    }

    .bookingsTable td {
        border: 1px solid #CCD5E6;
        text-align: center;
        padding: 8px;

    }

    td .leftAlign {
        text-align: left;
    }

</style>
<table width="98%" height="350" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td colspan="3">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="body_head1">&nbsp;</td>
                    <td align="center" class="body_bg1"><strong><?= $pageTitle ?></strong></td>
                    <td class="body_head2">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
        <td width="99%" align="center" valign="top">
            <table width="99%">
                <tr>
                    <td width="33%" height="17" valign="top" class="candylink">
                        <?php
                        if ($pageTitle == "Manage Bookings")
                        {
                            if ($_SESSION['IS_ADMIN'] == 1)
                            {
                                echo '<a href="add_booking.php" class="link2">[Add Manual Booking]</a>';
                            }
                            echo '<a href="deleted_bookings.php" class="link2">&nbsp;&nbsp;[View Deleted Bookings]</a>';
                        }
                        else
                        {
                            echo '<a href="booking_listing.php" class="link2">[Go Back to Current Bookings]</a>';
                        }
                        ?>
                    </td>
                    <td width="67%" align="right" valign="top" class="candylink"><span class="error">
      			      <?php
                      if (isset($_GET['opt']) && $_GET['opt'] == '1')
                          echo "Booking Successfully Created";
                      elseif (isset($_GET['opt']) && $_GET['opt'] == '2')
                          echo "Booking Successfully Modified";
                      elseif ($msg != '')
                          echo $msg;
                      ?>
      			    </span></td>
                </tr>
            </table>
            <form action="<?= $pageLink ?>" method="post" name="form2" id="form2">
                <table class="searchBar">
                    <tr>
                        <td width="33%" valign="middle" class="heading"> Unit Name: <select name="unit" id="unit">
                                <option <?php if (isset($_GET['unit']) && $_GET['unit'] == "") echo "Selected"; ?> value="">Please select</option>
                                <?php
                                $res = $dbc->getResult("Select * From cpefs_units Where unit_status=1 Order By unit_name", __LINE__, __FILE__);
                                while ($row = mysqli_fetch_array($res))
                                {
                                    ?>
                                    <option <?php if (isset($_GET['unit']) && $_GET['unit'] == $row['unit_id']) echo "Selected"; ?> value="<?php echo $row['unit_id'] ?>">
                                        <?php echo show_text($row['unit_name']) ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>&nbsp;

                            Member Name:
                            <input type="text" name="mem" value="<?php if (isset($_GET['mem'])) echo $_GET['mem'] ?>" class="inptbox" size="25"/>

                            Check-In-Date:
                            <input type="text" name="check_in_date" value="<?php if (isset($_GET['cdate'])) echo $_GET['cdate'] ?>" class="inptbox" size="20"/>

                            <a href="javascript:showCal('Calendar1')"><img src="images/calendar.png" align="absmiddle" alt="Select Check-in-date" border="0"></a>&nbsp;

                            Status: <select name="status">
                                <option <?php if (isset($_GET['status']) && $_GET['status'] == "") echo "Selected"; ?> value="">Please select</option>
                                <option <?php if (isset($_GET['status']) && $_GET['status'] == "0") echo "Selected"; ?> value="0">Un-approved</option>
                                <option <?php if (isset($_GET['status']) && $_GET['status'] == "1") echo "Selected"; ?> value="1">Pending</option>
                                <option <?php if (isset($_GET['status']) && $_GET['status'] == "2") echo "Selected"; ?> value="2">Confirmed</option>
                                <option <?php if (isset($_GET['status']) && $_GET['status'] == "3") echo "Selected"; ?> value="3">Cancelled</option>
                            </select> &nbsp;
                            <input type="image" src="images/search.png" align="absmiddle" alt="Search" title="Search" name="search1"/>
                        </td>
                    </tr>

                </table>
            </form>
        </td>
        <td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    </tr>
    <tr>
        <td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
        <td height="100%" width="100%" align="center" valign="top">
            <form action="" method="post" name="frm" id="frm">
                <table width="99%">
                    <tr align="center">
                        <td width="57%" height="10" align="right" valign="bottom"></td>
                        <td width="43%" height="10" colspan="3" align="right" valign="bottom">
                            <?php pagination($pageLink, $querystring, $totalPages, $page); ?>
                        </td>
                    </tr>
                </table>
                <table class="bookingsTable">
                    <tr>
                        <td width="8%" class="leftAlign heading"
                        ">
                        <a href="<?= $pageLink ?>?ord=<?php echo $ord_next_unit ?><?php echo $qrystringord ?>" class="link3">Unit Name</a>
                        <?php
                        if (isset($_GET["ord"]) && ($_GET["ord"] == 1 || $_GET["ord"] == 2))
                        {
                            ?>
                            &nbsp;
                            <a href="<?= $pageLink ?>?ord=<?php echo $ord_next_unit ?><?php echo $qrystringord ?>" class="link2">
                                <img src="images/<?php echo $img_name ?>.gif" width="12" height="11" alt="^" border="0"></a>
                            <?php
                        }
                        ?>
                        </td>
                        <td class="heading leftAlign">
                            <a href="<?= $pageLink ?>?ord=<?php echo $ord_next_mem ?><?php echo $qrystringord ?>" class="link3">Member Name</a>
                            <?php

                            if (isset($_GET["ord"]) && ($_GET["ord"] == 3 || $_GET["ord"] == 4))
                            {
                                ?>
                                &nbsp;
                                <a href="<?= $pageLink ?>?ord=<?php echo $ord_next_mem ?><?php echo $qrystringord ?>" class="link2"><img src="images/<?php echo $img_name ?>.gif" width="12" height="11" alt="^" border="0"></a>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="heading">
                            <a href="<?= $pageLink ?>?ord=<?php echo $ord_next_cdate ?><?php echo $qrystringord ?>" class="link3">Check In Date</a>
                            <?php
                            if (isset($_GET["ord"]) && ($_GET["ord"] == 5 || $_GET["ord"] == 6))
                            {
                                ?>
                                &nbsp;
                                <a href="<?= $pageLink ?>?ord=<?php echo $ord_next_cdate ?><?php echo $qrystringord ?>" class="link2"><img src="images/<?php echo $img_name ?>.gif" width="12" height="11" alt="^" border="0"></a>
                                <?php
                            }
                            ?>
                        </td>
                    <td class="heading">Rate</td>
                    <td class="heading">Deposit</td>
                    <td class="heading">Balance</td>
                    <td class="heading"># Nights</td>
                    <td class="heading">Date Booked</td>
                    <td class="heading">Date Confirmed</td>
                    <td class="heading">Period</td>
                    <td class="heading">Status</td>
                    <?php
                    if ($pageTitle == "Manage Bookings")
                    { ?>
                        <td class="heading">See Payments</td>
                        <td class="heading">Manage</td>
                        <?php
                    }
                    else
                    {
                        ?>
                        <td class="heading">Delete Booking</td>
                        <td class="heading">Restore Booking</td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                if ($rows > 0)
                {
                    $sql = $qry . " limit $start,$toshow";
                    $res = $dbc->getResult($sql, __LINE__, __FILE__, $qryParamsRefs);
                    $color = "";
                    while ($row = $res->fetch_array(MYSQLI_ASSOC))
                    {
                        if ($color == "bgc")
                            $color = "bgc2";
                        else
                            $color = "bgc";
                        ?>
                    <tr class="<?=$color?>">
                        <td class="leftAlign">
                            <?php
                            if($pageTitle=="Manage Bookings")
                            {?>
                            <a href="add_booking.php?id=<?=$row['booking_id']?>" title="Edit <?=show_text($row["member_name"])?>" class="link2">
                                <?=show_text($row["unit_name"])?>
                            </a>
                            <?php
                            }
                            else
                            {
                                echo show_text($row["unit_name"]);
                            }
                            ?>
                        </td>
                        <td class="leftAlign">
                            <?php
                            if($pageTitle=="Manage Bookings")
                            {?>
                        <a href="add_member.php?mode=edit&id=<?=$row["member_id"] ?>" title="Edit <?=show_text($row["member_name"])?>" class="link2">
                            <?=show_text($row["member_name"]) ?></a>
                                <?php
                            }
                            else
                            {
                                echo show_text($row["member_name"]);
                            }
                            ?>
                        </td>
                        <td>
                        <?=date("d M Y", strtotime($row["check_in_date"])); ?>
                        </td>
                        <td><?="$" . $row["rate"] ?></td>
                        <td>
                            <?php
                            if ($row["paid"] == "0.00")
                                echo "Nil";
                            else
                                echo "$" . number_format($row["paid"], 2);
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($row["rate"] == $row["paid"])
                                echo "Nil";
                            else
                                echo "$" . number_format(($row["rate"] - $row["paid"]), 2);
                            ?>
                        </td>
                        <td><?=$row["nights"] ?></td>
                        <td><?=date("d M Y", strtotime($row["booking_date"])); ?></td>
                        <td>
                            <?php
                            if ($row["confirm_date"] != "")
                                echo date("d M Y", strtotime($row["confirm_date"]));
                            else
                                echo "-";
                            ?>
                        </td>
                        <td class="header">
                            <input type="hidden" name="booking_id[]" value="<?php echo $row['booking_id'] ?>"/>
                            <select name="is_peak[]" class="dropdown">
                                <option value="S" <?php if ($row["is_peak_period"] == "S") echo "Selected"; ?>>Standard</option>
                                <option value="P" <?php if ($row["is_peak_period"] == "P") echo "Selected"; ?>>Peak</option>
                            </select>
                        </td>
                        <td>
                            <?php
                            if ($row["approve"] == 0)
                                echo "Un-approved";
                            elseif ($row["approve"] == 1)
                                echo "Pending";
                            elseif ($row["approve"] == 2)
                                echo "Confirmed";
                            elseif ($row["approve"] == 3)
                                echo "Cancelled";
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($pageTitle == "Manage Bookings")
                            {
                                ?>
                                <img src="images/files.png" title="See Payments" onClick="window.location.href='booking_payments.php?id=<?=$row['booking_id']?>'" style="cursor:pointer;" alt="See Payments">
                                <?php
                            }
                            else
                            {
                                ?>
                                <input type="hidden" name="id[]" value="<?= $row['booking_id'] ?>"/>
                                <input name="delete[]" type="checkbox" value="<?= $row['booking_id'] ?>" /><?php
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($pageTitle == "Manage Bookings")
                            {
                                getBookingListingManageLinks($row, $qstringall);
                            }
                            else
                            {
                                getDeletedBookingsManageLinks($row, $qstringall);
                            }

                            ?>
                        </td>
                    </tr>
                        <?php
                    }
                }
                else
                {
                    ?>
                    <tr>
                        <td colspan="12" height="100" valign="middle" class="error">
                            <b>No Booking Found!</b></td>
                    </tr>
                    <?php
                }
                ?>
</table>

<br/>
<table width="99%">
    <tr align="center">
        <td width="57%" height="10" align="right" valign="bottom">
            <?php
            if ($_SESSION['IS_ADMIN'] == 1)
                echo '<input name="submit" type="submit" class="button1" value="Submit" />';
            ?>
        </td>
        <td width="43%" height="10" colspan="3" align="right" valign="bottom">
            <?php pagination($pageLink, $querystring, $totalPages, $page); ?>
        </td>
    </tr>
</table>
</form>
