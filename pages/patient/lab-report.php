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
    $query = "SELECT [Lab_Report_ID], [Test_Type],[Rep_status], [Blood_Collected_Time], [Report_Relesed_Time]
              FROM [tbl_Lab_Test_Report] WHERE
              [Patient_ID] = ?";
    $stmt = sqlsrv_query($conn, $query, array($user_id));

    if ($stmt === false) {
        die('Error executing query: ' . print_r(sqlsrv_errors(), true));
    }

    // Display prescriptions in a table
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

        /* Message box styling */
.message-box {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #28a745;
    color: white;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}
/* Button styles */
.btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
    margin: 5px;
}

.btn:hover {
    background-color: #0056b3;
}

.download-btn {
    background-color: #28a745; /* Green for Download */
}

.download-btn:hover {
    background-color: #218838;
}
.print-btn {
    background-color:rgb(59, 40, 167); /* Green for Download */
}

.print-btn:hover {
    background-color:rgb(59, 40, 167);
}

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
                <h1><?php echo htmlspecialchars($userName); ?></h1>
                <h2>Lab Report Dashboard</h2>
            </div>
        </div>

        <div class="fieldsets">
            <div class="profile-container">
                <h1>Lab Report</h1>

               
            
            </div>
        </div>
        <div class="profile-container2">
        <?php echo isset($labReportTable) ? $labReportTable : '<p>No reports available.</p>'; ?>

    </div>
    </div>

    <script>
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

    
   
</script>
</body>
</html>
