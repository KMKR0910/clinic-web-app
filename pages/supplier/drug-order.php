<?php
session_start();

// Check if the user is logged in and retrieve Supplier_ID from the session
if (isset($_SESSION['Supplier_ID'])) {
    $supplierID = $_SESSION['Supplier_ID'];
    $Fname = $_SESSION['Fname'];
} else {
    // Redirect to login if the user is not logged in
    header("Location: SuppLog.php");
    exit();
}
?>


<?php
include "../../config/db.php";


$supplierID = $_SESSION['Supplier_ID'];
// Fetch orders
$orders = [];

    $sql = " SELECT [OrderID], [Order_Status], [Total_Amount], [Ordered_date], [Supplier_ID] FROM tbl_Drug_order WHERE [Supplier_ID]=? AND [Order_Status]='Doctor Confirmed' OR [Order_Status]='Supplier Confirmed' OR [Order_Status]='Order Shipped'  ORDER BY [Ordered_date] DESC";
    // Prepare and execute the query
$stmt = sqlsrv_query($conn, $sql, [$supplierID]);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true)); // Display errors if the query fails
}

// Fetch rows and store them in the $orders array
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $row['Ordered_date'] = $row['Ordered_date'] !== null ? $row['Ordered_date']->format('Y-m-d') : null;
    $orders[] = $row;
}



// Fetch order items
$orderItems = [];
if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];
    $sql = "SELECT ItemID, Drug_Name, Pack_Size, Quantity FROM tbl_Order_Item WHERE OrderID = ?";
    $params = [$orderID];
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) die(print_r(sqlsrv_errors(), true));

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $orderItems[] = $row;
    }
}

// Confirm order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmOrder'])) {
    $orderID = intval($_POST['orderID']); // Ensure it's an integer
    $totalCost = floatval($_POST['totalCost']);

    $sql = "UPDATE [tbl_Drug_order] SET [Total_Amount] = ?, [Order_Status] = 'Supplier Confirmed' WHERE [OrderID] = ?";
    $params = [$totalCost, $orderID];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) die(print_r(sqlsrv_errors(), true));

    $confirmationMessage = "Order confirmed successfully.";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderShipped'])) {
    $orderID = intval($_POST['orderID']); // Ensure it's an integer


    $sql = "UPDATE [tbl_Drug_order] SET [Order_Status] = 'Order Shipped' WHERE [OrderID] = ?";
    $params = [$orderID];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) die(print_r(sqlsrv_errors(), true));

    $confirmationMessage = "Order details updated succesfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <link rel="stylesheet" href="../../css/supplier-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
<link rel="stylesheet" href="../../css/supplier-record.css">
</head>


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
            <div class="header--wrapper">
                <div class="header--title">
                    <h1>Drug Order Dashbaord</h1>
                </div></div>

                <div class="fieldsets">
                <div class="profile-container">
    

    <!-- Orders Table -->
 
    <table id="orderTable">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Status</th>
                <th>Total Amount</th>
                <th>Ordered Date</th>
                
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['OrderID'] ?></td>
                    <td><?= $order['Order_Status'] ?></td>
                    <td><?= $order['Total_Amount'] ?></td>
                    <td><?= $order['Ordered_date'] !== null ? htmlspecialchars($order['Ordered_date']) : 'N/A' ?></td>

                  
                    <td><button onclick="fetchOrderItems(<?= $order['OrderID'] ?>)">View Items</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Order Items Table -->
    <?php if (!empty($orderItems)): ?>
        <h2>Order Items</h2>
        <table id="orderItemsTable">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Drug Name</th>
                    <th>Pack Size</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?= $item['ItemID'] ?></td>
                        <td><?= $item['Drug_Name'] ?></td>
                        <td><?= $item['Pack_Size'] ?></td>
                        <td><?= $item['Quantity'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div><div class="profile-container2">
    <!-- Confirm Order Form -->
    <h2>Confirm Order</h2>
    <form id="confirmForm" method="POST" action="">
        <div class="form-group">
            <label for="totalCost">Total Cost:</label>
            <input type="number" id="totalCost" name="totalCost" step="0.01"required>
        </div>
        <input type="hidden" id="confirmOrderID" name="orderID" value="<?= isset($_GET['orderID']) ? $_GET['orderID'] : '' ?>">
<input type="submit" name="confirmOrder" value="Confirm Order" onclick="return confirmOrder()">
    </form>
    <form id="orderShipped" method="POST" action="">
       
        <input type="hidden" id="shipOrderID" name="orderID" value="<?= isset($_GET['orderID']) ? $_GET['orderID'] : '' ?>">
<input type="submit" name="orderShipped" value="Order Shipped" onclick="return orderShipped()">
    </form>
</div>
    <?php if (isset($confirmationMessage)): ?>
        <p style="color: green;"><?= $confirmationMessage ?></p>
    <?php endif; ?>
    </di></div>

    <script src="../../js/drug-order.js"></script>
</body>
</html>
