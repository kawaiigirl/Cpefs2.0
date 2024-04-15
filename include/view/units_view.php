<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead();

AddHeader_StartMain(GetNavLinks());
?>

<div class="row"><h1 class="header">Units</h1>
    <div class="midColumn">
<div class="card">
    <table style="width: 100%; border-collapse: collapse">
        <tr>
          <td></td>
          <td height="30" colspan="3"><div align="center">
              <strong>Rates (including linen) </strong>
            </div></td>
            <td></td>
        </tr>
        <tr>
          <td height="30"><strong>Unit</strong></td>
          <td height="30"><div align="center">
              <strong>Basic</strong>
            </div></td>
          <td height="30"><div align="center">
             <strong>Peak</strong>
            </div></td>
          <td height="30"><div align="center">
              <strong>Weekends</strong>
            </div></td>
          <td></td>
        </tr>
		<?php
        global $dbc;
		$res=$dbc->getResult("Select * From cpefs_units Where unit_status=1 Order By unit_name",__LINE__,__FILE__);
        $bg = "grey";
		while($row=$res->fetch_array(MYSQLI_ASSOC))
		{
            if($bg=="#e0e0e0")
                $bg = "white";
            else
                $bg = "#e0e0e0"
		?>
        <tr style="background-color: <?=$bg?>">
          <td height="55"><a href="unit_details.php?id=<?php echo $row['unit_id']?>"><?php echo show_text($row['unit_name'])?></a></td>
          <td height="55"><div align="center"><?php echo "$".$row['basic_rate']?></div></td>
          <td height="55"><div align="center"><?php echo "$".$row['peak_rate']?></div></td>
          <td height="55"><div align="center"><?php echo "$".$row['weekend_rate']?></div></td>
          <td height="55"><img src="include/view/images/book_now.jpg" alt="Book Now" width="71" height="17" onClick="window.location.href='make_booking.php?id=<?php echo $row['unit_id']?>'"></td>
        </tr>
		<?php
		}
		?>
      </table>
      <p>All units are a minimum 3 night stay.</p>
      <p>Click a unit name for specific accommodation details.</p>
      </div>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>
</html>