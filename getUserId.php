<?php
session_start();

require('db.php');
header('Content-Type: application/json');

if(isset($_SESSION['user_id'])) {
    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Create a new PDO instance (replace with your own database details)
    $dbh = new PDO('mysql:host=localhost;dbname=t3_alberto', 'admin232m', '6WO7hcod~Pn8^umu');

    // Prepare a SQL statement to select the first_name from the users table where the uuid equals the user ID
    $stmt = $dbh->prepare("SELECT client_firstname FROM clients WHERE user_id = $userId");

    // Bind the user ID to the SQL statement and execute it
    $stmt->execute();

    // Fetch the row from the database
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userRow) {
        // If a row was returned, output the first_name and user_id as a JSON object
        echo json_encode(['first_name' => $userRow['client_firstname'], 'user_id' => $userId]);
    } else {
        // If no row was returned, the user ID was not found in the database
        echo json_encode(['error' => 'User not found']);
    }
} else {
    echo json_encode(['error' => 'Not logged in']);
}
?>