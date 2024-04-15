<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>

<div class="row">
    <h1 class="header">Unit Availability</h1>
    <div class="card">
        <a href="unit_availability.php?yr=<?php echo($prevyr) ?>&mnth=<?php echo($prevmnth) ?>#cal" class="calendarlink">&lt;
            previous month &gt;</a>
        &nbsp;
        <strong><?php echo $monnn . " " . $year; ?></strong>&nbsp;
        <a href="unit_availability.php?yr=<?php echo($nextyr) ?>&mnth=<?php echo($nextmnth) ?>#cal" class="calendarlink">&lt;
            next month &gt;</a>
        </p>
        <table width="100%" border="1" cellpadding="2" cellspacing="0"
               style="border-collapse:collapse; border: #1f2320 1px">
            <tr>
                <td height="18">
                    <div align="center"></div>
                </td>
                <?php
                $unit = array();
                while ($row1 = mysqli_fetch_array($units))
                {

                    $unit[] = $row1['unit_id'];
                    ?>
                    <td width="130" height="18">
                        <div align="center"><strong><?php echo $row1['unit_name'] ?></strong></div>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
            $daye = 1;
            if (count($unit) > 0)
            {
                while ($daye <= $daysIM)
                {
                    ?>
                    <tr>
                        <td height="18" align="left" style="padding-left:3px;">
                            <strong><?php echo date("d, D", mktime(0, 0, 0, $mon, $daye, $year)); ?></strong></td>
                        <?php
                        for ($i = 0; $i < count($unit); $i++)
                        {
                            echo getLink($daye, $mon, $year, $unit[$i]);
                        }
                        ?>
                    </tr>
                    <?php
                    $daye++;
                }
            }
            ?>
        </table>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>