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
       <span class="calendar-month"><strong>${monthNames[month - 1]} ${year}</strong></span>
<button onclick="previousMonth()"><strong>&#8249;</strong></button><button onclick="nextMonth()"><strong>&#8250;</strong></button>
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
        dayElement.setAttribute("id", 'day'+day);
        dayElement.textContent = day;
        if ([5, 6].includes((startingOffset + day - 1) % 7)) {
            dayElement.classList.add('weekend'); // Add class for weekend days
        }
        const currentDateObject = new Date(year, month - 1, day);
        if (currentDateObject < new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate())) {
            dayElement.classList.add('disabled'); // Disable days before today
        } else {
            dayElement.addEventListener('click', () => {
                selectDate(currentDateObject,'day'+day);
            });
        }
        daysElement.appendChild(dayElement);
    }
}

function selectDate(date,clickedOnDayElement) {
    const selectedDay = calendarElement.querySelector('.selected');
    if (selectedDay) {
        selectedDay.classList.remove('selected');
    }

    selectedDate = date;

    const newSelectedDay =  document.getElementById(clickedOnDayElement);
    newSelectedDay.classList.add('selected');
    updateInput();
}

function updateInput() {
    const inputField = document.getElementById('check_in_date');
    if (selectedDate) {
        inputField.value = selectedDate.toLocaleDateString('en-GB');
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