<?php
session_start();
include "../config/db.php";

 //Initialize variables
$newPatientID = null;

// Query to get the last Patient ID
$query = "SELECT TOP 1 [Patient ID] FROM [tbl_patient_info] ORDER BY [Patient ID] DESC";
$stmt = sqlsrv_query($conn, $query);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $lastUserID = $row['Patient ID'];
} else {
    $lastUserID = null;
}

// Generate the new Patient ID
if (empty($lastUserID)) {
    $newPatientID = "P00001";
} else {
    $numericPart = substr($lastUserID, 1); // Extract the numeric part
    $newNumericPart = (int)$numericPart + 1; // Increment the numeric part
    $newPatientID = "P" . str_pad($newNumericPart, 5, "0", STR_PAD_LEFT); // Add leading zeros
}

// Free the statement
sqlsrv_free_stmt($stmt);


// Retrieve form data using POST method
$name = $_POST['username'];
$address = $_POST['address'];
$DOB = $_POST['dob'];
$gender = $_POST['gender'];
$Cno = $_POST['contact'];
$email = $_POST['email'];
$password = $_POST['password']; // Ensure 'password' is captured correctly

// Initialize registration status
$is_registration_successful = false;

// Check if the contact number already exists
$check_Cno_sql = "SELECT * FROM [tbl_patient_info] WHERE [Contact Number] = ?";
$params = array($Cno);
$check_Cno_stmt = sqlsrv_query($conn, $check_Cno_sql, $params);

if ($check_Cno_stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($check_Cno_stmt)) {
    echo "Error: The contact number already exists. Please use a different contact number.";
} else {
    // If contact is unique, proceed with the insertion
    $sql = "INSERT INTO  [tbl_patient_info] ([Patient ID],[Name], [Contact Number], [Email], [Password], [Gender], [Address], [DOB]) VALUES (?, ?, ?, ?, ?, ?, ?,?)";
    $params = array($newPatientID,$name, $Cno, $email, $password, $gender, $address, $DOB);
    $insert_stmt = sqlsrv_query($conn, $sql, $params);

    if ($insert_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        // Change the status to true if the query is successful
        $is_registration_successful = true;
        $_SESSION['name'] = $name;
        $_SESSION['patient_id'] = $newPatientID; 

        // Retrieve the user ID of the newly inserted user
        $query = "SELECT SCOPE_IDENTITY() AS user_id";
        $result = sqlsrv_query($conn, $query);
        if ($result !== false && sqlsrv_fetch($result)) {
            $user_id = sqlsrv_get_field($result, 0);
            $_SESSION['user_id'] = $user_id;
        }

        echo "New record created successfully";
    }
}

// Close the connection
sqlsrv_free_stmt($check_Cno_stmt);
sqlsrv_free_stmt($insert_stmt);
sqlsrv_close($conn);

// Redirect to the dashboard or display the error based on the success of registration
if ($is_registration_successful) {
    header("Location: login-patient.html");
    exit();
} else {
    // Handle the error case
    echo "Registration failed!";
}
?>
