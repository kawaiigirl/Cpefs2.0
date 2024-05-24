<!DOCTYPE html>
<html lang="en">
<?php
AddGenericHead("","<link rel='stylesheet' href='include/view/calendar.css'>");

AddHeader_StartMain(GetNavLinks());
?>

<div class="row">
    <h1 class="header">Unit Availability</h1>
    <div class="singleColumn">
        <div class="card">
            <div style="text-align: center">
                <div style="position: relative">
                <div id="currentMonth" style="position: relative"><h2 class="header"><?=$monthName. " ". $year ?></h2></div>
                    <button class="availability-calendar prev-unit change-month change-month-large" onclick="changeMonth(-1)"><strong>&#8249;</strong> Previous Month</button>
                    <button class="availability-calendar prev-unit change-month change-month-small" onclick="changeMonth(-1)"><strong>&#8249;</strong></button>
                    <button class="availability-calendar next-unit change-month change-month-large" onclick="changeMonth(1)">Next Month <strong>&#8250;</strong></button>
                    <button class="availability-calendar next-unit change-month change-month-small" onclick="changeMonth(1)"><strong>&#8250;</strong></button>
                </div>
                <?php
                displayCombinedCalendar($year, $month);
                ?>
            </div>
        </div>
        <div class="card">
            <h3>Key</h3>
            <span class="available-link unit-1 key"> Available</span><span class="pending unit-1 key">Pending</span><span class="unavailable unit-1 key">Unavailable</span>
            <p>Click on an available date to book that unit.</p>
        </div>
    </div>
</div>
<?php
AddFooter_CloseMain();
?>

<script>
    function toggleNavLinks() {
        let navLinks = document.querySelector('.nav-links');
        navLinks.classList.toggle('show');
    }

    function changeMonth(offset) {
        let currentMonthElement = document.getElementById('currentMonth');
        let currentMonth = currentMonthElement.innerText;
        let [month, year] = currentMonth.split(' ');

        month = getMonthNumber(month);

        let newDate = new Date(`${year}-${month}-01`);
        newDate.setMonth(newDate.getMonth() + offset);

        let newYear = newDate.getFullYear();

        reloadWithMonth(newDate.getMonth() + 1, newYear);
    }

    function reloadWithMonth(selectedMonth, selectedYear) {
        // Get the current URL and remove any existing month and year parameters
        const currentUrl = window.location.href.split('?')[0];

        // Append the selected month and year as new parameters
        // Reload the page with the new URL
        window.location.href = `${currentUrl}?month=${selectedMonth}&year=${selectedYear}`;
    }

    function getMonthNumber(monthName) {
        return new Date(monthName + ' 1, 2000').getMonth() + 1;
    }
</script>
</html>