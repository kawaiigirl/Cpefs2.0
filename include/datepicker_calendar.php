<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .calendar {
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .calendar-header button {
            cursor: pointer;
            background: none;
            border: none;
            font-size: 16px;
        }
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }
        .calendar-day {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
            cursor: pointer;
        }
        .calendar-day.weekend {
            background-color: #f0f0f0; /* Light gray background for weekends */
        }
        .selected {
            background-color: lightblue;
        }
        .disabled {
            pointer-events: none;
            color: #ccc;
        }
    </style>
</head>
<body>

<div class="calendar" id="calendar"></div>
<span class="calendar-month"><strong>${monthNames[month - 1]}<br>${year}</strong></span>
<button onclick="previousMonth()"><strong>&#8249;</strong></button><button onclick="nextMonth()"><strong>&#8250;</strong></button>
</div>
<script>
    const calendarElement = document.getElementById('calendar');
    let selectedDate = null;
    let currentYear = null;
    let currentMonth = null;

    function renderCalendar(year, month) {
        currentYear = year;
        currentMonth = month;
        const currentDate = new Date(year, month - 1, 1);
        const lastDay = new Date(year, month, 0).getDate();

        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']; // Adjusted to start with Monday

        calendarElement.innerHTML = `
      <div class="calendar-header">
        <button onclick="previousMonth()">&#8249;</button>
        <h2>${monthNames[month - 1]} ${year}</h2>
        <button onclick="nextMonth()">&#8250;</button>
      </div>
      <div class="calendar-days">
        ${dayNames.map(day => `<div class="calendar-day">${day}</div>`).join('')}
      </div>
    `;

        const daysElement = calendarElement.querySelector('.calendar-days');

        // Calculate the offset to start with Monday
        const startingOffset = (currentDate.getDay() === 0) ? 6 : currentDate.getDay() - 1;

        // Add blank spaces for the days before the first day of the month
        for (let i = 0; i < startingOffset; i++) {
            const blankDay = document.createElement('div');
            blankDay.classList.add('calendar-day');
            daysElement.appendChild(blankDay);
        }

        // Add days of the month
        for (let day = 1; day <= lastDay; day++) {
            const dayElement = document.createElement('div');
            dayElement.classList.add('calendar-day');
            dayElement.textContent = day;
            if ([5, 6].includes((startingOffset + day - 1) % 7)) {
                dayElement.classList.add('weekend'); // Add class for weekend days
            }
            const currentDateObject = new Date(year, month - 1, day);
            if (currentDateObject < new Date(currentYear, currentMonth - 1, new Date().getDate())) {
                dayElement.classList.add('disabled'); // Disable days before today
            } else {
                dayElement.addEventListener('click', () => {
                    selectDate(currentDateObject);
                });
            }
            daysElement.appendChild(dayElement);
        }
    }

    function selectDate(date) {
        const selectedDay = calendarElement.querySelector('.selected');
        if (selectedDay) {
            selectedDay.classList.remove('selected');
        }
        selectedDate = date;
        const dayElements = calendarElement.querySelectorAll('.calendar-day');
        dayElements.forEach(dayElement => {
            dayElement.classList.remove('selected');
        });
        const firstDayOfMonth = new Date(currentYear, currentMonth - 1, 1).getDay();
        const selectedDayElement = calendarElement.querySelector(`.calendar-day:nth-child(${date.getDate() + firstDayOfMonth})`);
        selectedDayElement.classList.add('selected');
        updateInput();
    }

    function updateInput() {
        const inputField = document.getElementById('selected-date');
        if (selectedDate) {
            const formattedDate = selectedDate.toLocaleDateString('en-US');
            inputField.value = formattedDate;
        } else {
            inputField.value = '';
        }
    }

    function previousMonth() {
        if (currentMonth === 1) {
            renderCalendar(currentYear - 1, 12);
        } else {
            renderCalendar(currentYear, currentMonth - 1);
        }
    }

    function nextMonth() {
        if (currentMonth === 12) {
            renderCalendar(currentYear + 1, 1);
        } else {
            renderCalendar(currentYear, currentMonth + 1);
        }
    }

    // Initialize calendar
    const today = new Date();
    renderCalendar(today.getFullYear(), today.getMonth() + 1);
</script>

<input type="text" id="selected-date" placeholder="Select a date">

</body>
</html>
