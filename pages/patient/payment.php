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

    // Get the selected date from the date picker
   

    // SQL query to get prescriptions for the selected date and patient ID
    $query = "SELECT [Date], [Payment Type],[Total_Cost]
              FROM [tbl_Patient_Payment] WHERE
              [patirnt_ID] = ?";
    $stmt = sqlsrv_query($conn, $query, array($user_id));

    if ($stmt === false) {
        die('Error executing query: ' . print_r(sqlsrv_errors(), true));
    }

    // Display prescriptions in a table
    $billDetails = '<table class="profile-table">';
    $billDetails .= '<tr><th>Date</th><th>Description</th><th>Amount</th></tr>';

   
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $billDetails .= '<tr>';
        $billDetails .= '<td>' . htmlspecialchars(date_format($row['Date'], 'Y-m-d')) . '</td>';

        $billDetails .= '<td>' . htmlspecialchars($row['Payment Type']) . '</td>';
        $billDetails .= '<td>' .  htmlspecialchars($row['Total_Cost']) . '</td>';
       
        /*
        // Nested table for additional details
        $billDetails .= '<td>';
        $billDetails .= '<table class="nested-table">';
      
        $billDetails .= '</table>';
        $billDetails .= '<button type="submit" name="download"class="btn download-btn">Download</button>';
        $billDetails .= '<button type="submit" name="print" class="btn print-btn">Print</button>';
        $billDetails .= '</td>';
        $billDetails .= '</tr>'; // Correct closing tag here*/
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
                <h1>Payement History</h1>
                
            </div>
        </div>

        <div class="fieldsets">
            
        </div>
        <div class="profile-container2">
        <?php echo isset($billDetails) ? $billDetails : '<p>No reports available.</p>'; ?>

    </div>
    </div>

   <!-- <script>
    // Message display function
function showMessage(message) {
    const messageBox = document.createElement('div');
    messageBox.className = 'message-box';
    messageBox.innerText = message;
    document.body.appendChild(messageBox);

    // Remove message after 3 seconds
    setTimeout(() => {
        messageBox.remove();
    }, 3000);
}

// Attach event listener to download buttons
document.querySelectorAll('.download-btn').forEach(button => {
    button.addEventListener('click', () => {
        showMessage('Downloaded Successfully!');
    });
});
    document.querySelectorAll('.print-btn').forEach(button => {
    button.addEventListener('click', () => {
        showMessage('Print Successfully!');
    });
});

    
   
</script>-->
</body>
</html>
