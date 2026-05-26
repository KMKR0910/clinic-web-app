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

<body>
    <div class="sidebar">
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
    </div>

    </div>

    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h1>Prescription Dashboard</h1>
              
            </div>
        </div>

        <div class="fieldsets">
            <div class="profile-container">
                

                <!-- Date Picker -->
                <label for="datePicker">Select Date:</label>
                <input type="date" id="datePicker" name="date" value="<?php echo date('Y-m-d'); ?>">

            
            </div>
        </div>
            <div class="profile-container2">
        <!-- Prescription data will be displayed here -->
    </div>
</div>

<script src="../../js/prescription.js"></script>
</body>
</html>
