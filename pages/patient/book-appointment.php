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
    <title>Book Appointment</title>
    <link rel="stylesheet" href="../../css/patient-dashboard.css">
    <link rel="stylesheet" href="../../css/book-appointment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
</head>
<body>

<script>
        // Keep the session alive every 10 minutes (600000 milliseconds)
        setInterval(function() {
            fetch('keep_session_alive.php'); // Call the PHP script to keep session alive
        }, 600000);
    </script>
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
                <h2>Book Appointment</h2>
            </div>
        </div>

        <div class="fieldsets">
            <div class="profile-container">
            <div class="container">

<form method="post" action="">
<label for="appointment_date">Select Date:</label>
<input type="date" id="appointment_date" name="appointment_date" required>
<button type="submit" name="check-Avaliability">Check Availability</button>
</form>
    
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include "../../config/db.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Check if booking button is clicked
if (isset($_POST["check-Avaliability"])) {


$selectedDate = $_POST["appointment_date"];





// Query to check for the appointment date and retrieve appointment number
$sql = "SELECT TOP 1 [AppointmentNumber], [StartTime],[SessionID],[SessionDate]
        FROM [DoctorSessions]
        WHERE CAST([SessionDate] AS DATE) = ? AND [AppointmentStatus] = 'Avaliable'
        ORDER BY [StartTime] ASC";
$params = [$selectedDate];

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
die(print_r(sqlsrv_errors(), true));
}

// Fetch results
$appointments = [];

// Fetch results and store them in the appointments array
while ($appointment = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
$appointments[] = $appointment;
}




$_SESSION['appointments'] = $appointments;

sqlsrv_free_stmt($stmt);

}}
?>



<?php if (isset($appointments)) { ?>
<h3>Appointments on <?php echo htmlspecialchars($selectedDate); ?>:</h3>
<?php if (empty($appointments)) { ?>
    <p>No appointments available.</p>
<?php } else { ?>
    <p>Doctor available.</p>
    
    <table class="profile-table">
    <tr>
        <th>Appointment Number</th>
        <th>Start Time</th>
        <th>Session ID</th>
        <th>Session Date</th>
    </tr>
    <?php foreach ($appointments as $appointment) { ?>
        <tr>
        <td><?php echo htmlspecialchars($appointment['AppointmentNumber']); ?></td>
        <td><?php echo htmlspecialchars($appointment['StartTime']->format('Y-m-d H:i:s')); ?></td> <!-- Format StartTime -->
        <td><?php echo htmlspecialchars($appointment['SessionID']); ?></td>
        <td><?php echo htmlspecialchars($appointment['SessionDate']->format('Y-m-d H:i:s')); ?></td>
        </tr>
    <?php } ?>
</table>
    


</form>

<?php } ?>
<?php } ?>


<form method="POST" action="">
    <input type="hidden" name="appointment_ID" value="<?php echo htmlspecialchars($sessionID); ?>">
<button type="submit" name="book_appointment">Book a number </button></form>
<?php




if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Check if booking button is clicked
if (isset($_POST["book_appointment"])) {
// Get the appointment number
//$appointmentID = $_POST["appointment_ID"];
if (!empty($_SESSION['appointments'])) {
    $appointments = $_SESSION['appointments'];
    $appointmentNumber = $appointments[0]['AppointmentNumber'];
    $startTime = $appointments[0]['StartTime'];
    $sessionID = $appointments[0]['SessionID'];
    $sessionDate = $appointments[0]['SessionDate'];
    
}
$pID = $_SESSION['user_id'];
include "../../config/db.php";

// Prepare the booking query

// Assuming you have a table for booked appointments (e.g., "BookedAppointments") and want to store the appointment number and booking details.
if ($sessionDate && $startTime && $appointmentNumber) {
$sql = "INSERT INTO [tbl_appoinment] ([Date], [time],[Appoinment Number],[status],[Patient ID]) VALUES (?,?,?,'Pending',?)";
$params = [$sessionDate,$startTime,$appointmentNumber,$pID];

// Execute the query
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Query execution failed: " . print_r(sqlsrv_errors(), true));

}
sqlsrv_free_stmt($stmt);
echo "<p>Appointment booked successfully!</p>";
}else   {
echo "<p>Error: Missing appointment details.</p>";
}

sqlsrv_close($conn);

// Redirect or show success message after booking



}
}
?>
                
                   

        
            </div>
            </div>
</body>


</html>
