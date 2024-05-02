<script>

stm_bm(["menu0a6a",730,"images/","blank.gif",0,"","",1,0,250,0,1000,1,0,0,"","100%",0,0,1,2,"default","hand","/scripts/"],this);

stm_bp("p0",[0,4,0,0,8,5,0,7,100,"",-2,"",-2,50,0,0,"#999999","","navTopBck.jpg",3,0,0,"#000000"]);

stm_ai("p0i0",[0,"CPEFS home","","",-1,-1,0,"index.php","_self","","CPEFS home","","",0,0,0,"","",0,0,0,0,1,"",0,"",0,"","",3,3,0,0,"#FFFFF7","#000000","#FFFFFF","#7AA30E","9pt Verdana","9pt Verdana",0,0]);

stm_ai("p0i1",[6,1,"#000000","",-1,-1,0]);

stm_aix("p0i2","p0i0",[0,"units","","",-1,-1,0,"units.php","_self","","units","","",0,38,0,"arrow_r.gif","arrow_r.gif",7,7]);

stm_bpx("p1","p0",[1,4,0,0,4,1,0,0]);

</script>

<?php

global $dbc;
$resource=$dbc->getResult("Select * From cpefs_units Where unit_status=1 Order By unit_name",__LINE__,__FILE__);

while($records=$resource->fetch_array(MYSQLI_ASSOC))

{

?>

	<script>

	stm_aix("p1i0","p0i0",[0,"<?php echo $records['unit_name']; ?>","","",-1,-1,0,"details.php?id=" + "<?php echo $records['unit_id'];?>","_self","","<?php echo $records['unit_name'];?>","","",0,38]);

	</script>

<?php

}

?>

<script>

stm_ep();

stm_aix("p0i3","p0i1",[]);

stm_aix("p0i4","p1i0",[0,"unit availability","","",-1,-1,0,"availability.php","_self","","unit availability"]);
stm_aix("p0i5","p0i1",[]);
stm_aix("p0i6","p1i0",[0,"gallery","","",-1,-1,0,"gallery.php","_self","","gallery"]);
stm_aix("p0i7","p0i1",[]);
stm_aix("p0i8","p1i0",[0,"my bookings","","",-1,-1,0,"bookings.php","_self","","my bookings"]);
stm_aix("p0i9","p0i1",[]);
stm_aix("p0i10","p1i0",[0,"make a booking","","",-1,-1,0,"book-unit.php","_self","","make a booking"]);
stm_aix("p0i11","p0i1",[]);
stm_aix("p0i12","p1i0",[0,"contact us","","",-1,-1,0,"contact.php","_self","","contact us"]);
stm_aix("p0i13","p0i1",[]);
stm_aix("p0i14","p1i0",[0,"legals","","",-1,-1,0,"legals.php","_self","","legals"]);



stm_ep();

stm_em();

</script>