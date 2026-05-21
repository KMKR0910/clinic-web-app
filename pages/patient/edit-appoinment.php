<?php
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['name'])) {
    $userName = $_SESSION['name'];
} else {
    $userName = "Guest"; // Default if not logged in
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Data</title>
    <link rel="stylesheet" href="../../css/patient-dashboard.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Your CSS styles here */
        body {
            background-color: #e3f2fd;
            font-family: Arial, sans-serif;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-container2 {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-container3 {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .profile-table th, .profile-table td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }
        .profile-table th {
            background-color: #f2f2f2;
        }
        .profile-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .profile-table tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="sidebar">
    <div class="sidebar">
        <div class="logo">
            <ul class="menu">
                <div class="sidebar">
        <div class="logo">
            <ul class="menu">
                <li class="active">
                    <a href="patient-dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="patient-profile.php">
                        <i class="fas fa-user-alt"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="book-appointment.php">
                        <i class="fas fa-calendar-check"></i>
                        <span>Appointments</span>
                    </a>
                </li>
                <li>
                    <a href="prescription.php">
                        <i class="fas fa-file-prescription"></i>
                        <span>Prescription</span>
                    </a>
                </li>
                <li>
                    <a href="lab-report.php">
                        <i class="fas fa-vial"></i>
                        <span>Lab Results</span>
                    </a>
                </li>
                <li>
                    <a href="diagnose.php">
                        <i class="fas fa-history"></i>
                        <span>Diagnose History</span>
                    </a>
                </li>
                <li>
                    <a href="payment.php">
                        <i class="fas fa-history"></i>
                        <span>Payment History</span>
                    </a>
                </li>
                <li class="logout">
                    <a href="../home-page.html">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Log Out</span>
                    </a>
                </li>
            </ul>
        </div>
            </ul>
        </div>
    </div>

    </div>

    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h1><?php echo htmlspecialchars($userName); ?></h1>
                <h2>Appoinment Dashboard</h2>
            </div>
        </div>

        <div class="fieldsets">
            <div class="profile-container">
                <h1>Appoinment</h1>

            </div>
        <div class="profile-container2">
                <?php


// Include database connection file
include "../../config/db.php";
// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $selectedDate = new DateTime(); // Get today's date
    $selectedDate->modify('-5 days'); // Subtract 5 days
    $selectedDate = $selectedDate->format('Y-m-d'); // Format the date to 'YYYY-MM-DD'
    
    // SQL query to get prescriptions for the selected date and patient ID
    $query = "SELECT [Appoinment Number], [Date], [time], [status]
              FROM [tbl_appoinment]
              WHERE [date] >= ? AND [Patient ID] = ?";
    $stmt = sqlsrv_query($conn, $query, array($selectedDate, $user_id));

    if ($stmt === false) {
        die('Error executing query: ' . print_r(sqlsrv_errors(), true));
    }

    // Display prescriptions in a table
    
    echo '<table class="profile-table" id="appointmentTable">';
    echo '<tr><th>Appointment Number</th><th>Date</th><th>Time</th><th>Status</th></tr>';

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['Appoinment Number']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Date']->format('Y-m-d')) . '</td>';
        if ($row['time'] instanceof DateTime) {
            echo '<td>' . htmlspecialchars($row['time']->format('H:i A')) . '</td>';
        } else {
            echo '<td>' . htmlspecialchars($row['time']) . '</td>';
        }
        echo '<td>' . htmlspecialchars($row['status']) . '</td>';
        echo '</tr>';
    }

    echo '</table>';


    // Free the statement and close the connection
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else {
    // If user is not logged in, redirect to login page
    header("Location: SuppLog.php");
    exit();
}
?>

            
            
        </div>
       
        <div class="profile-container3">
        <form action="login.php" method="POST">
                <!-- Input Fields for Selected Row -->
<h3>Selected Appointment Details</h3>
<label for="appointmentNumber">Appointment Number:</label>
<input type="text" id="appointmentNumber" readonly><br><br>

<label for="date">Date:</label>
<input type="text" id="date" readonly><br><br>

<label for="time">Time:</label>
<input type="text" id="time" readonly><br><br>

<label for="status">Status:</label>
<input type="text" id="status" readonly><br><br>

<input type="hidden" name="appointmentNumberHidden" id="appointmentNumberHidden">

                
<button type="submit" name="delete-appoinment"> Delete Appoinment</button>
                
            </form>

    
    <script>
   document.addEventListener('DOMContentLoaded', () => {

 
    document.querySelectorAll('#appointmentTable tr').forEach((row, index) => {
        if (index === 0) return; // Skip header row
        row.addEventListener('click', () => {
            const cells = row.querySelectorAll('td');
            const appointmentNumber = cells[0].textContent.trim();
            const date = cells[1].textContent.trim();
            const time = cells[2].textContent.trim();
            const status = cells[3].textContent.trim();
           
            document.getElementById('appointmentNumber').value = appointmentNumber;
            document.getElementById('date').value = date;
            document.getElementById('time').value = time;
            document.getElementById('status').value = status;

            document.getElementById('appointmentNumberHidden').value = appointmentNumber;
        });
        });
    });
});

</script>



<?php
//delete appoinment php

// Include database connection file
include "../../config/db.php";

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

   
    //$appointmentNumber = $_POST['appointmentNumberHidden'];
    $appointmentNumber = intval($_POST['appointmentNumberHidden']);
    // SQL query to get prescriptions for the selected date and patient ID
    $query = "DELETE FROM 
               [tbl_appoinment]
              WHERE [date] >= ? AND [Patient ID] = ? AND [Appoinment Number] = ? ";
    $stmt = sqlsrv_query($conn, $query, array($selectedDate, $user_id,$appointmentNumber));

    if ($stmt === false) {
        die('Error executing query: ' . print_r(sqlsrv_errors(), true));
    }

    // Display prescriptions in a table
    
    echo '<table class="profile-table" id="appointmentTable">';
    echo '<tr><th>Appointment Number</th><th>Date</th><th>Time</th><th>Status</th></tr>';

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['Appoinment Number']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Date']->format('Y-m-d')) . '</td>';
        if ($row['time'] instanceof DateTime) {
            echo '<td>' . htmlspecialchars($row['time']->format('H:i A')) . '</td>';
        } else {
            echo '<td>' . htmlspecialchars($row['time']) . '</td>';
        }
        echo '<td>' . htmlspecialchars($row['status']) . '</td>';
        echo '</tr>';
    }

    echo '</table>';


    // Free the statement and close the connection
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else {
    // If user is not logged in, redirect to login page
    header("Location: SuppLog.php");
    exit();
}
?>
</div>
</class>
</body>
</html>
