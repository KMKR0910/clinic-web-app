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
// Database connection
include "../../config/db.php";
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];}

// Get patient ID from query string
//$patientID = isset($_GET['id']) ? $_GET['id'] : 1; // Default ID for testing

// Handle delete request
if (isset($_POST['delete'])) {
    $deleteSQL = "DELETE FROM [dbo].[tbl_patient_info] WHERE [Patient ID] = ?";
    $deleteStmt = sqlsrv_query($conn, $deleteSQL, [$user_id]);

    if ($deleteStmt) {
        header("Location: ../home-page.html"); // Redirect to a list page after deletion
        exit();
    } else {
        $message = "Error deleting profile: " . print_r(sqlsrv_errors(), true);
    }
}

// Handle form submission to update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    $updateSQL = "UPDATE [dbo].[tbl_patient_info] 
                  SET [Name] = ?, [Address] = ?, [DOB] = ?, [Contact Number] = ?, [Email] = ?, [Gender] = ? 
                  WHERE [Patient ID] = ?";
    $params = [$name, $address, $dob, $contact, $email, $gender, $user_id];
    $updateStmt = sqlsrv_query($conn, $updateSQL, $params);

    if ($updateStmt) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . print_r(sqlsrv_errors(), true);
    }
}

// Fetch patient details
$sql = "SELECT [Patient ID], [Name], [Address], [DOB], [Contact Number], [Email], [Gender] 
        FROM [dbo].[tbl_patient_info] 
        WHERE [Patient ID] = ?";
$params = [$user_id];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false || sqlsrv_fetch($stmt) === false) {
    die("Error retrieving data: " . print_r(sqlsrv_errors(), true));
}

$patient = [
    'id' => sqlsrv_get_field($stmt, 0),
    'name' => sqlsrv_get_field($stmt, 1),
    'address' => sqlsrv_get_field($stmt, 2),
    'dob' => sqlsrv_get_field($stmt, 3)->format('Y-m-d'),
    'contact' => sqlsrv_get_field($stmt, 4),
    'email' => sqlsrv_get_field($stmt, 5),
    'gender' => sqlsrv_get_field($stmt, 6)
];
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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color:#e3f2fd;
        }
        .header--wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    }
    .header--title {
    display: flex;
    flex-direction: column; /* keeps h1 above h2 */
    }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .profile-info label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .profile-info p {
            margin: 0 0 15px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        .profile-info input,
        .profile-info select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .actions {
            text-align: center;
        }
        .actions button {
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-btn {
            background-color: #007BFF;
            color: #fff;
        }
        .save-btn {
            background-color: #28a745;
            color: #fff;
        }
        .cancel-btn {
            background-color: #6c757d;
            color: #fff;
        }
        .message {
            text-align: center;
            color: green;
            font-weight: bold;
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
                <h1>Profile Information</h1>
               
            </div></div>

    <div class="container">
      
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
        <form method="POST" id="profileForm">
            <div class="profile-info">
                <label>Name:</label>
                <p id="view-name"><?= htmlspecialchars($patient['name']) ?></p>
                <input type="text" name="name" id="edit-name" value="<?= htmlspecialchars($patient['name']) ?>" style="display: none;">

                <label>Email:</label>
                <p id="view-email"><?= htmlspecialchars($patient['email']) ?></p>
                <input type="email" name="email" id="edit-email" value="<?= htmlspecialchars($patient['email']) ?>" style="display: none;">

                <label>Phone:</label>
                <p id="view-contact"><?= htmlspecialchars($patient['contact']) ?></p>
                <input type="text" name="contact" id="edit-contact" value="<?= htmlspecialchars($patient['contact']) ?>" style="display: none;">

                <label>Address:</label>
                <p id="view-address"><?= htmlspecialchars($patient['address']) ?></p>
                <input type="text" name="address" id="edit-address" value="<?= htmlspecialchars($patient['address']) ?>" style="display: none;">

                <label>Date of Birth:</label>
                <p id="view-dob"><?= htmlspecialchars($patient['dob']) ?></p>
                <input type="date" name="dob" id="edit-dob" value="<?= htmlspecialchars($patient['dob']) ?>" style="display: none;">

                <label>Gender:</label>
                <p id="view-gender"><?= htmlspecialchars($patient['gender']) ?></p>
                <select name="gender" id="edit-gender" style="display: none;">
                    <option <?= $patient['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option <?= $patient['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
            <div class="actions">
                <button type="button" class="edit-btn" id="editButton">Edit Profile</button>

                <button type="submit" class="save-btn" id="saveButton" style="display: none;">Save Changes</button>
                <button type="button" class="cancel-btn" id="cancelButton" style="display: none;">Cancel</button>
                 <button type="submit" name="delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete this profile?');">Delete Profile</button>
            </div>
        </form>
    </div></div>

    <script>
        const editButton = document.getElementById('editButton');
        const saveButton = document.getElementById('saveButton');
        const cancelButton = document.getElementById('cancelButton');
        const viewElements = document.querySelectorAll('p[id^="view-"]');
        const editElements = document.querySelectorAll('input, select');

        editButton.addEventListener('click', () => {
            viewElements.forEach(el => el.style.display = 'none');
            editElements.forEach(el => el.style.display = 'block');
            editButton.style.display = 'none';
            saveButton.style.display = 'inline-block';
            cancelButton.style.display = 'inline-block';
        });

        cancelButton.addEventListener('click', () => {
            viewElements.forEach(el => el.style.display = 'block');
            editElements.forEach(el => el.style.display = 'none');
            editButton.style.display = 'inline-block';
            saveButton.style.display = 'none';
            cancelButton.style.display = 'none';
        });
    </script>
</body>
</html>

