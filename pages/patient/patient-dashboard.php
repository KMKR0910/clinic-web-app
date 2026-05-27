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
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="../../css/patient-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .active {
            background-color: #A4C8E1; /* Highlight for active menu item */
        }
       
    </style>
</head>
<body>
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

    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h1>Welcome, <?php echo htmlspecialchars($userName); ?></h1> <!-- Use htmlspecialchars for security -->
                <h2>Patient Dashboard</h2>
            </div>
        </div>

        <div class="fieldset1">
            <!-- Appointment Booking Section -->
            <fieldset class="b1">
                <div class="booking">
                    <h3>                         </h3>
                    <img src="../../images/patient-home-4.png" class="app1" alt="Appointment">
                    <h3><a href="book-appointment.php" class="b11">Book a Appointment</a></h3>
                </div>
            </fieldset>

            <!-- Edit Appointment Section -->
            <fieldset class="b2">
                <div class="booking2">
                    <h3></h3>
                    <img src="../../images/patient-home-2.jpg" class="app1" alt="Appointment">
                    <h3><a href="edit-appoinment.php" class="b11">View Appointment</a></h3>
                </div>
            </fieldset>

            
            <fieldset class="b2">
                <div class="booking">
                    <h3></h3>
                    <img src="../../images/patient-home-5.png" class="app1" alt="Appointment">
                    <h3><a href="lab-report.php" class="b11">View Laboratory Tests</a></h3>
                </div>
            </fieldset>

        </div>

        <!--<fieldset class="confirm">
    <h1 class="head"><u>Patient Information</u></h1>
    Message and details will be displayed here 
    <div id="patient-message"></div>
    <div id="patient-details"></div>
    <div id="error-message" style="color: red;"></div>
</fieldset>-->

    </div>

    <!-- JavaScript for handling appointment details -->
    <script>
        // Function to get query parameters from URL
       // Function to get query parameters from URL
function getQueryParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

const patientID = <?php echo json_encode( $_SESSION['name']); ?>;
fetch(`getPatientDetails.php?patient_id=${patientID}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('patient-message').textContent = "Patient ID: " + data.patient_id;
                    document.getElementById('patient-details').innerHTML = `
                        <p><strong>Name:</strong> ${data.name}</p>
                        <p><strong>Contact Number:</strong> ${data.contact}</p>
                        <p><strong>Address:</strong> ${data.address}</p>
                        <p><strong>Date of Birth:</strong> ${data.dob}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Gender:</strong> ${data.gender}</p>`;
                } else {
                    document.getElementById('patient-message').textContent = "Patient information not available.";
                }
            })
            .catch(error => {
                document.getElementById('error-message').textContent = "Error fetching patient information.";
                console.error('Error:', error);
            });
            const errorMessage = getQueryParameter('error_message');
        if (errorMessage) {
            document.getElementById('error-message').textContent = errorMessage;
        }
    </script>

    </script>

</body>
</html>
