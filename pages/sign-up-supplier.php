<?php
session_start();
include "../config/db.php";
 //Initialize variables





// Retrieve form data using POST method
$name = $_POST['username'];
$companyName = $_POST['companyname'];
$address = $_POST['address'];
$Cno = $_POST['contact'];
$email = $_POST['email'];
$password = $_POST['password']; // Ensure 'password' is captured correctly

// Initialize registration status
$is_registration_successful = false;

// Check if the contact number already exists
$check_Cno_sql = "SELECT * FROM [tbl_drug_supplier] WHERE [Contact_Number]= ?";
$params = array($Cno);
$check_Cno_stmt = sqlsrv_query($conn, $check_Cno_sql, $params);

if ($check_Cno_stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($check_Cno_stmt)) {
    echo "Error: The contact number already exists. Please use a different contact number.";
} else {
    // If contact is unique, proceed with the insertion
    $sql = "INSERT INTO  tbl_drug_supplier ([Supplier_Name], [Company_Name], [Company_Address], [Contact_Number],[Email],[Password]) VALUES (?, ?, ?, ?, ?, ?)";
    $params = array($name,$companyName, $address,$Cno, $email, $password);
    $insert_stmt = sqlsrv_query($conn, $sql, $params);

    
    if ($insert_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        // Redirect to the login page after successful registration
        header("Location: login-supplier.html");
        exit();
    }}

// Fetch the inserted Supplier_ID
  

// Close the connection
sqlsrv_free_stmt($check_Cno_stmt);
sqlsrv_free_stmt($insert_stmt);
sqlsrv_close($conn);

?>
