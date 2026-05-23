<?php
session_start();

// Check if the user is logged in and retrieve Fname from the session
if (isset($_SESSION['Fname'])) {
    $Fname = $_SESSION['Fname'];
} else {
    // Redirect to login if the user is not logged in
    header("Location: SuppLog.php");
    exit();
}
?>

<html>
    <head>
 <link rel="stylesheet" href="../../css/supplier-dashboard.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            <div class="header--wrapper">
                <div class="header--title">
                    <h1>Welcome,<?php echo htmlspecialchars($Fname); ?></h1>
                    <h2>Drug Supplier Dashboard</h2>
                </div>
                <div class="user--info">
                    <!--<div class="search--box">
                        <i class="fa-solid fa-search"></i>
                    </div>-->
                    <img src="supplier-home-3.jpg" alt="User Image">
                </div>
            </div>

            <div class="fieldset1">
                <fieldset class="b1">
                    <div class="booking">
                        <h3></h3>
                        <img src="../../images/supplier-home-1.jpg" class="app1" alt="Drug Order"><br>
                        <h3><a href="drug-order.php" class="b11">View Drug Orders</a></h3>
                    </div>
                </fieldset><br><br>

                <fieldset class="b2">
                    <div class="booking2">
                        <h3></h3>
                        <img src="../../images/supplier-home-2.jpg" class="app1" alt="Appointment"><br>
                        <h3><a href="profile.php" class="b11">View Profile</a></h3>
                    </div>
                </fieldset>

               
            </div>
        </div>
    </body>
</html>
