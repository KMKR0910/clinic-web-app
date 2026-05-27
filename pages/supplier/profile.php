<?php
session_start();

// Check if the user is logged in and retrieve Supplier_ID from the session
if (isset($_SESSION['Supplier_ID'])) {
    $supplierID = $_SESSION['Supplier_ID'];
} else {
    // Redirect to login if the user is not logged in
    header("Location: login.php");
    exit();
}
?>
<?php
// Include the database connection file
include "../../config/db.php";

// Handle delete request
if (isset($_POST['delete'])) {
    $deleteSQL = "DELETE FROM [dbo].[tbl_drug_supplier] WHERE [Supplier_ID] = ?";
    $deleteStmt = sqlsrv_query($conn, $deleteSQL, [$supplierID]);

    if ($deleteStmt) {
        header("Location: ../home-page.html"); 
        exit();
    } else {
        $message = "Error deleting profile: " . print_r(sqlsrv_errors(), true);
    }
}

// Handle form submission to update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $supplierName = $_POST['Sname'];
        $companyName = $_POST['Cname'];
      
        $email = $_POST['email'];
        $contact = $_POST['contact'];

        $updateSql = "UPDATE [tbl_drug_supplier] 
        SET [Supplier_Name] = ?, [Company_Name] = ?, [Email] = ? ,[Contact_Number] = ?
        WHERE [Supplier_ID] = ?";
$updateParams = array($supplierName, $companyName, $email,$contact, $supplierID);

$updateStmt = sqlsrv_query($conn, $updateSql, $updateParams);
    if ($updateStmt) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . print_r(sqlsrv_errors(), true);
    }
}


$sql = "SELECT [Supplier_ID], [Company_Name], [Supplier_Name], [Contact_Number], [Email] FROM [tbl_drug_supplier] WHERE [Supplier_ID] = ?";
$params = array($supplierID);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false || sqlsrv_fetch($stmt) === false) {
    die("Error retrieving data: " . print_r(sqlsrv_errors(), true));
}

$supplier = [
    'id' => sqlsrv_get_field($stmt, 0),
    'Cname' => sqlsrv_get_field($stmt, 1),
    'Sname' => sqlsrv_get_field($stmt, 2),
    
    
    'contact' => sqlsrv_get_field($stmt, 3),
    'email' => sqlsrv_get_field($stmt, 4),
    
];






?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
 <link rel="stylesheet" href="../../css/supplier-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="../../css/supplier-record.css">
</head>
<body>

<div class="sidebar">
            <div class="logo">
                <ul class="menu">
                    <li class="active">
                        <a href="dashboard.php" >
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                    <a href="profile.php">
                       
                            <i class="fas fa-user-alt"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                    <a href="drug-order.php">
                            <i class="fas fa-chart-bar"></i>
                            <span>Drug Orders</span>
                        </a>
                    </li>
                    <li class="logout">
                    <a href="../home-page.html">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Log out</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    <div class="main--content">
        <class="profile-container">

            <h1>Profile Information</h1>
         

          
            <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
            <form method="POST" id="profileForm">
          
            
            <div class="profile-info">
            <label>Company Name:</label>
                <p id="view-name"><?= htmlspecialchars($supplier['Cname']) ?></p>
                <input type="text" name="Cname" id="edit-name" value="<?= htmlspecialchars($supplier['Cname']) ?>" style="display: none;">

                <label>Email:</label>
                <p id="view-email"><?= htmlspecialchars($supplier['email']) ?></p>
                <input type="email" name="email" id="edit-email" value="<?= htmlspecialchars($supplier['email']) ?>" style="display: none;">

                <label>Contact Number:</label>
                <p id="view-contact"><?= htmlspecialchars((string)$supplier['contact'] ?? '') ?></p>
                <input type="text" name="contact" id="edit-contact" value="<?= htmlspecialchars((string)$supplier['contact'] ?? '') ?>" style="display: none;">

                

                
                <label>Supplier Name:</label>
                <p id="view-sname"><?= htmlspecialchars($supplier['Sname']) ?></p>
                <input type="text" name="Sname" id="edit-sname" value="<?= htmlspecialchars($supplier['Sname']) ?>" style="display: none;">
                

               
            </div>
        

          <div class="actions">
                <button type="button" class="edit-btn" id="editButton">Edit Profile</button>

                <button type="submit" class="save-btn" id="saveButton" style="display: none;">Save Changes</button>
                <button type="button" class="cancel-btn" id="cancelButton" style="display: none;">Cancel</button>
                 <button type="submit" name="delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete this profile?');">Delete Profile</button>
            </div>
          <!--<form method="POST" action="downloadProfile.php">
                <input type="hidden" name="Supplier_ID" value="<?php echo htmlspecialchars($supplierData['Supplier_ID']); ?>">
                <input type="hidden" name="Fname" value="<?php echo htmlspecialchars($supplierData['Supplier_Name']); ?>">
              
                <input type="hidden" name="Company_name" value="<?php echo htmlspecialchars($supplierData['Company_Name']); ?>">
                <input type="hidden" name="Address" value="<?php echo htmlspecialchars($supplierData['Company_Address']); ?>">
                <input type="hidden" name="Email_Address" value="<?php echo htmlspecialchars($supplierData['Email']); ?>">
                <button type="submit" class="download-btn">Download Profile</button>
            </form>-->
    </>
    </form>
        </div>
   
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
