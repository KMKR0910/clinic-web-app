<?php
session_start();

include "../../config/db.php";


// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Get the selected date from the date picker
    if (isset($_POST['date'])) {
        $selectedDate = $_POST['date'];
    } else {
        $selectedDate = date('Y-m-d'); // Default to today's date
    }

    // SQL query to get prescriptions for the selected date and patient ID
    $query = "SELECT [PrescriptionNumber], [Medicine], [Dosage], [Duration], [date] 
              FROM [tbl_prescript]
              WHERE [date] = ? AND [patientid] = ?";
    $stmt = sqlsrv_query($conn, $query, array($selectedDate, $user_id));

    if ($stmt === false) {
        die('Error executing query: ' . print_r(sqlsrv_errors(), true));
    }

    // Display prescriptions in a table
    echo '<table class="profile-table">';
    echo '<tr><th>Medicine</th><th>Dosage</th><th>Duration</th><th>Date</th></tr>';

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['Medicine']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Dosage']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Duration']) . '</td>';
        echo '<td>' . htmlspecialchars($row['date']->format('Y-m-d')) . '</td>';
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
