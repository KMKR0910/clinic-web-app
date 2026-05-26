// Keep session alive every 10 minutes
setInterval(function () {
    fetch('keep_session_alive.php', {
        method: 'POST',
        credentials: 'include'
    });
}, 600000);

// Prevent past dates
document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById("appointment_date");

    if (dateInput) {
        const today = new Date().toISOString().split("T")[0];
        dateInput.setAttribute("min", today);
    }
});

// Confirm booking
document.addEventListener("DOMContentLoaded", function () {
    const bookBtn = document.querySelector("button[name='book_appointment']");

    if (bookBtn) {
        bookBtn.addEventListener("click", function (e) {
            const ok = confirm("Do you want to book this appointment?");
            if (!ok) {
                e.preventDefault();
            }
        });
    }
});


document.addEventListener('DOMContentLoaded', function () {

    const table = document.getElementById('appointmentTable');

    if (!table) return;

    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        rows[i].addEventListener('click', function () {

            const cells = this.getElementsByTagName('td');

            document.getElementById('appointmentNumber').value = cells[0].innerText;
            document.getElementById('date').value = cells[1].innerText;
            document.getElementById('time').value = cells[2].innerText;
            document.getElementById('status').value = cells[3].innerText;

            document.getElementById('appointmentNumberHidden').value = cells[0].innerText;
        });
    }
});
