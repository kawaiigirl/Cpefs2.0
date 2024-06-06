<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead("","<link rel='stylesheet' href='include/view/fading.css'>");

AddHeader_StartMain(GetNavLinks());
?>
<div class="row"><h1 class="header">Units</h1>
    <div class="singleColumn">
<div class="card">
    <div class="pic-container">
        <div class="pic" id="pic4"><div class="text">Peninsula</div></div>
        <div class="pic" id="pic3"><div class="text">Focus</div></div>
        <div class="pic" id="pic2"><div class="text">Coco Bay</div></div>
        <div class="pic" id="pic1"><div class="text">Beach Haven</div></div>
    </div>
    <table class="unit-table" style="width: 100%; border-collapse: collapse">
        <tr>
            <th></th>
            <th colspan="3"><strong>Rates (including linen)</strong></th>
            <th></th>
        </tr>
        <tr>
          <th><strong>Unit</strong></th>
          <th style="width: 18%"><strong>Basic</strong></th>
          <th style="width: 18%"><strong>Peak</strong></th>
          <th style="width: 18%"><strong>Weekends</strong></th>
          <th></th>
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
          <td><a class="nav-link" style="display:block; width: 100%; text-align: center" href="unit_details.php?id=<?=$row['unit_id']?>"><?=show_text($row['unit_name'])?></a></td>
          <td><?="$".$row['basic_rate']?></td>
          <td><?="$".$row['peak_rate']?></td>
          <td><?="$".$row['weekend_rate']?></td>
            <td><a class="nav-link book-now" href="make_booking.php?id=<?=$row['unit_id']?>">Book Now</a></td>
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