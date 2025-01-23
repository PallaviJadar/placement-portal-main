<?php
// Starting the session
session_start();

// Adding the database file
require_once("Placement-Portal-main.php");

// Query to select all users
$sql = "SELECT * FROM users";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through the results
    while ($row = $result->fetch_assoc()) {
        echo $row['Name'] . "<br>"; // Display each user's name
    }
} else {
    echo "No users found.";
}

?>
