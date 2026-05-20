<?php
session_start();
include "../config/db.php"; // Database connection

// Retrieve login form data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';


// Validate input
if (empty($email) || empty($password)) {
    echo "Please fill in all fields.";
    exit();
}

try {
    
    // SQL query to fetch user details based on the provided email
    $sql = "SELECT * FROM [tbl_patient_info] WHERE [Email] = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array($email));

    if (!$stmt) {
        throw new Exception("Failed to prepare SQL statement: " . print_r(sqlsrv_errors(), true));
    }

    // Execute the statement
    if (sqlsrv_execute($stmt)) {
        // Check if a matching user is found
        if (sqlsrv_has_rows($stmt)) {
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

            // Manually compare the entered password with the database password
            if ($password === $row['Password']) {
                // Successful login
                session_regenerate_id(true); // Secure the session

                // Store user details in the session
                $_SESSION['user_id'] = $row['Patient ID']; // Patient ID
                $_SESSION['name'] = $row['Name'];         // Name

                // Redirect to the dashboard
                header("Location: patient/patient-dashboard.php");
                exit();
            } else {
                // Incorrect password
                echo "<script>alert('Invalid email or password.');</script>";
              
            }
        } else {
            // Email not found
            echo "<script>alert('Invalid email or password.');</script>";
            
        }
    } else {
        throw new Exception("Failed to execute SQL statement: " . print_r(sqlsrv_errors(), true));
    }
} catch (Exception $e) {
    // Log the error for debugging
    error_log($e->getMessage());
    echo "An error occurred. Please try again later.";
}

// Close the statement and connection
if (isset($stmt)) {
    sqlsrv_free_stmt($stmt);
}
sqlsrv_close($conn);
?>
