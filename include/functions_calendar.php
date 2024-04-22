<?php
function DisplayDate($date)
{

    if($date != "")
        return date("d-m-Y",strtotime($date));
    return "";
}
function isFirstDayofWeek($firstDayOfWeek,$day): bool
{
 return (((int)$firstDayOfWeek + $day - 2) % 7 == 0 || $day == 1);

}
function getAllUnitsAvailability($year, $month): array
{
    global $dbc;
    $availability = array();
    $units = getUnitsArray();

    foreach($units as $unit)
    {
        $availability[$unit['unit_id']]['name'] = $unit['unit_name'];
        for($day = 1; $day <= 31; $day++)
        {
            $check_date = date('Y-m-d', strtotime( $year.'-'.$month.'-'.$day));

            $sql = "Select * from cpefs_booking Where  TO_DAYS(?) >= TO_DAYS(check_in_date)
		  And TO_DAYS(?)<= TO_DAYS(check_in_date)+nights-1 And unit_id=? And approve<>3";
            $res = $dbc->getResult($sql, __LINE__, __FILE__, array("ssi", &$check_date, &$check_date, &$unit['unit_id']));
            $row = $res->fetch_array(MYSQLI_ASSOC);
            if ($res->num_rows > 0)
            {
                if ($row['approve'] == 1 || $row['approve'] == 0)
                    $availability[$unit['unit_id']]['availability'][$year.'-'.$month.'-'.$day] = 'Pending';
                elseif ($row['approve'] == 2)
                    $availability[$unit['unit_id']]['availability'][$year.'-'.$month.'-'.$day] = false;
            }
            else
            {
                $sqlP = "Select * from cpefs_peak_periods Where TO_DAYS(?) >= TO_DAYS(peak_period_start_date)
			    And TO_DAYS(?)<= TO_DAYS(peak_period_end_date)";
                $res = $dbc->getResult($sqlP, __LINE__, __FILE__, array("ss", &$check_date, &$check_date));
                if ($res->num_rows)
                {
                    $availability[$unit['unit_id']]['availability'][$year . '-' . $month . '-' . $day] = 'peak';
                }
                else
                {
                    $availability[$unit['unit_id']]['availability'][$year . '-' . $month . '-' . $day] = 'available';
                }
            }

        }
    }
    return $availability;
}

function displayCombinedCalendar($year, $month): void
{
    $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
    $firstDayOfWeek = date('N', mktime(0, 0, 0, $month, 1, $year));

    $unitsAvailability = getAllUnitsAvailability($year, $month);

    echo '<div class="calendar-wrapper">';
    echo '<table>';
    echo '<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr>';
    echo '<tr>';

    for ($i = 1; $i < $firstDayOfWeek; $i++) {
        echo '<td></td>';
    }

    for ($day = 1; $day <= $daysInMonth; $day++) {
        echo '<td>';
        echo $day;
        foreach ($unitsAvailability as $unitId => $unitData) {
            $unitName = $unitData['name'];
            $availability = $unitData['availability'];
            $date = "$year-$month-$day";
            if (((int)$firstDayOfWeek + $day - 2) % 7 == 0 || $day == 1)
            {
                $fontOpacity="";
            }
            else
            {
                $fontOpacity = " style='color: rgba(0, 0, 0, 0.0)'";
            }

            $isAvailable = $availability[$date] ?? false;
            if ($isAvailable) {
                // Display unit name only on the first day of each week

                echo '<a href="make_booking.php?unit=' . $unitId . '&year=' . $year . '&month=' . $month . '&date=' . $date . '" class="unit-name available-link unit-' . $unitId . '"'.$fontOpacity.'>';
              // if(isFirstDayofWeek($firstDayOfWeek,$day))
               {
                   echo $unitName;
               }
              // else
                   echo " ";
                echo '</a>';

            } else {
                echo '<span class="unit-name unavailable-day unit-' . $unitId . '"'.$fontOpacity.'>';
                //if(isFirstDayofWeek($firstDayOfWeek,$day))
                {
                    echo $unitName;
                }
               // else
                    echo " ";
                echo '</span>';
            }
        }

        echo '</td>';

        if (((int)$firstDayOfWeek + $day - 1) % 7 == 0) {
            echo '</tr><tr>';
        }
    }

    echo '</tr>';
    echo '</table>';
    echo '</div>';
}