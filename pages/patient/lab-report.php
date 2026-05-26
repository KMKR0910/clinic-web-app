<?php
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['name'])) {
    $userName = $_SESSION['name'];
} else {
    $userName = "Guest"; // Default if not logged in
}
?>

<?php


// Include database connection file
include "../../config/db.php";

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];


   

    // SQL query to get prescriptions for the selected date and patient ID
    $query = "SELECT [Lab_Report_ID], [Test_Type],[Rep_status], [Blood_Collected_Time], [Report_Relesed_Time]
              FROM [tbl_Lab_Test_Report] WHERE
              [Patient_ID] = ?";
    $stmt = sqlsrv_query($conn, $query, array($user_id));

    if ($stmt === false) {
        die('Error executing query: ' . print_r(sqlsrv_errors(), true));
    }

   
    $labReportTable = '<table class="profile-table">';
    $labReportTable .= '<tr><th>Test Type</th><th>Status</th><th>Blood Collected Date</th><th>Report Released Date</th><th>Actions</th></tr>';

   
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $labReportTable .= '<tr>';
        $labReportTable .= '<td>' . htmlspecialchars($row['Test_Type']) . '</td>';
        $labReportTable .= '<td>' . htmlspecialchars($row['Rep_status']) . '</td>';
        $labReportTable .= '<td>' . ($row['Blood_Collected_Time'] ? htmlspecialchars($row['Blood_Collected_Time']->format('Y-m-d H:i:s')) : 'N/A') . '</td>';
        $labReportTable .= '<td>' . ($row['Report_Relesed_Time'] ? htmlspecialchars($row['Report_Relesed_Time']->format('Y-m-d H:i:s')) : 'N/A') . '</td>';
        
        // Nested table for additional details
        $labReportTable .= '<td>';
        $labReportTable .= '<table class="nested-table">';
      
        $labReportTable .= '</table>';
        $labReportTable .= '<button type="submit" name="download"class="btn download-btn">Download</button>';
        $labReportTable .= '<button type="submit" name="print" class="btn print-btn">Print</button>';
        $labReportTable .= '</td>';
        $labReportTable .= '</tr>'; // Correct closing tag here
    }

    
    // Free the statement and close the connection
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else {
    // If user is not logged in, redirect to login page
    header("Location: SuppLog.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report Data</title>
    <link rel="stylesheet" href="../../css/patient-dashboard.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../css/patient-record.css">
 </style>
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
        
                <h1>Lab Report Dashboard</h1>
            </div>
        </div>

        <div class="profile-container2">
        <?php echo isset($labReportTable) ? $labReportTable : '<p>No reports available.</p>'; ?>

    </div>
    </div>

    
    
<script src="../../js/lab-report.js"></script>
    
   

</body>
</html>
