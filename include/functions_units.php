<?php
function getUnits()
{
    global $dbc;
    return $dbc->getResult("Select * From cpefs_units Where unit_status=1 Order By unit_name",__LINE__,__FILE__);
}
function getUnitsArray(): array
{
    global $dbc;
    return$dbc->getArrayResult("Select * From cpefs_units Where unit_status=1 Order By unit_name",__LINE__,__FILE__);
}
function getUnitName($unitID): string
{
    return match ($unitID)
    {
        '1' => "beachhaven",
        '2' => "cocobay",
        '3' => "focus",
        '5' => "peninsular",
        default => "",
    };
}
function getNextUnit(int $unitID): int
{
    return match ($unitID)
    {
        1 => 2,
        2 => 3,
        3 => 5,
        default => 1,
    };
}
function getPreviousUnit(int $unitID): int
{
    return match ($unitID)
    {
        1 => 5,
        3 => 2,
        5 => 3,
        default => 1,
    };
}
function getNumberOfImagesForUnit($unitID): int
{
    return match ($unitID)
    {
        '1', '2', '3' => 20,
        default => 7,
    };
}
function getUnitStandardRate($unit_id,$check_in_date,$nights):float
{
    $unitRatesArray = getUnitRates($unit_id);
    switch($nights)
    {
        case 3:
        case 4:
            return $unitRatesArray['weekend_rate'];
        case 7:
            if(Booking::isPeakPeriod($check_in_date, $nights))    /* It is a peak period */
            {
                return $unitRatesArray['peak_rate'];
            }
            else
            {
                return $unitRatesArray['basic_rate'];
            }
        default:
            return 0;
    }
}
function getUnitRates(int $unit_id): array
{
    global $dbc;
    $row=$dbc->getSingleRow("Select * from cpefs_units where unit_id=?",__LINE__,__FILE__,array('i',&$unit_id));
    return array('basic_rate'=> $row['basic_rate'],'weekend_rate'=> $row['weekend_rate'],'peak_rate'=>$row['peak_rate']);
}
function getUnitsSelectOptions($unit_id=""): void
{
    global $dbc;
    ?><option <?php if($unit_id=="")echo "Selected";?> value="">Please select</option>
    <?php

    $res=$dbc->getResult("Select * From cpefs_units Where unit_status=1 Order By unit_name",__LINE__,__FILE__) ;
    while($row=$res->fetch_array(MYSQLI_ASSOC))
    {
        ?>
        <option <?php if ($unit_id==$row['unit_id'])echo "Selected";?> value="<?php echo $row['unit_id']?>">
            <?php echo show_text($row['unit_name'])?>
        </option>
        <?php
    }

}