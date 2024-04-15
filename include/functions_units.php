<?php
function getUnits()
{
    global $dbc;
    return $dbc->getResult("Select * From cpefs_units Where unit_status=1 Order By unit_name",__LINE__,__FILE__);
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