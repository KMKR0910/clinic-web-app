<?php
session_start();
                // Include database connection file
                include "../../config/db.php";

                // Check if the user is logged in
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];

                    // Get the selected date from the date picker
                    if (isset($_POST['date'])) {
                        $selectedDate = $_POST['date'];
                    } else {
                        $selectedDate = date('Y-m-d'); // Default to today's date
                    }

                    // SQL query to get prescriptions for the selected date and patient ID
                    $query = "SELECT [DiagnosNumber], [Date], [Description], [Allergies]
                              FROM [tbl_diagnostic_data]
                              WHERE [Date] = ? AND [patient_id] = ?";
                    $stmt = sqlsrv_query($conn, $query, array($selectedDate, $user_id));

                    if ($stmt === false) {
                        die('Error executing query: ' . print_r(sqlsrv_errors(), true));
                    }

                    // Display prescriptions
                    echo '<table class="profile-table">';
                    echo '<tr><th>Diagnose Number</th><th>Description</th><th>Allergies</th><th>Date</th></tr>';

                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['DiagnosNumber']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Description']) . '</td>';
                    
                        echo '<td>' . htmlspecialchars($row['Allergies']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Date']->format('Y-m-d')) . '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';

                    // Free the statement
                    sqlsrv_free_stmt($stmt);
                       // Close the database connection
                sqlsrv_close($conn);
                } else {
                    // If user is not logged in, redirect to login page
                    header("Location: SuppLog.php");
                    exit();
                }
                
             
                ?>