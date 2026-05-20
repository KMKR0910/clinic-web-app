<?php
session_start();
include "../../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SELECT query to check login credentials
    $sql = "SELECT [Supplier_ID], [Supplier_Name] FROM [tbl_drug_supplier] WHERE [Email] = ? AND [Password] = ?";
    $params = array($email, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Check if a matching record is found
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Save supplier details to the session
        $_SESSION['Fname'] = $row['Supplier_Name'];
        $_SESSION['Supplier_ID'] = $row['Supplier_ID'];

        // Redirect to the relevant dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Display an error message if login fails
        echo "<script>alert('Invalid email or password.');</script>";
    }

    // Close the connection
    sqlsrv_close($conn);
} else {
    echo "Invalid request method.";
}
?>
