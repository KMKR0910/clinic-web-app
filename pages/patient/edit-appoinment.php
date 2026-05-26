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
    <link rel="stylesheet" href="../../css/patient-record.css">
</head>

<script src="../../js/appointment.js"></script>
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
                <h1>Appoinment Dashboard</h1>
                
            </div>
        </div>

        <div class="fieldsets">
           
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
    
   
    $query = "SELECT [Appoinment Number], [Date], [time], [status]
              FROM [tbl_appoinment]
              WHERE [date] >= ? AND [Patient ID] = ?";
    $stmt = sqlsrv_query($conn, $query, array($selectedDate, $user_id));

    if ($stmt === false) {
        die('Error executing query: ' . print_r(sqlsrv_errors(), true));
    }

   
    
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
        <form action="" method="POST">
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

    
   


<?php
include "../../config/db.php";


if (isset($_SESSION['user_id']) && isset($_POST['delete-appoinment'])) {

    $user_id = $_SESSION['user_id'];

    if (!empty($_POST['appointmentNumberHidden'])) {

        $appointmentNumber = intval($_POST['appointmentNumberHidden']);

        $query = "DELETE FROM [tbl_appoinment]
                  WHERE [Patient ID] = ?
                  AND [Appoinment Number] = ?";

        $params = [$user_id, $appointmentNumber];

        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);

        echo "<script>
                alert('Appointment deleted successfully');
                window.location.href = window.location.href;
              </script>";

    } else {
        echo "<script>alert('No appointment selected');</script>";
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<script>alert('Invalid request');</script>";
}
?>
</div>
</class>
</body>
</html>
