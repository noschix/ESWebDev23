<?php
session_start();

require('db.php');
header('Content-Type: application/json');

if(isset($_SESSION['user_id'])) {
    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Create a new PDO instance (replace with your own database details)
    $dbh = new PDO('mysql:host=localhost;dbname=t3_alberto', 'admin232m', '6WO7hcod~Pn8^umu');

    // Prepare a SQL statement to select the role from the users table where the uuid equals the user ID
    $roleStmt = $dbh->prepare("SELECT role FROM users WHERE uuid = :userId");

    // Bind the user ID to the SQL statement and execute it
    $roleStmt->bindParam(':userId', $userId);
    $roleStmt->execute();

    // Fetch the role from the database
    $roleRow = $roleStmt->fetch(PDO::FETCH_ASSOC);

    if ($roleRow) {
        $role = $roleRow['role'];
        $firstNameStmt = null;
        
        if ($role === 'client') {
            // Prepare a SQL statement to select the first_name from the clients table
            $firstNameStmt = $dbh->prepare("SELECT client_firstname AS firstname FROM clients WHERE user_id = :userId");
        } else if ($role === 'agent') {
            // Prepare a SQL statement to select the first_name from the agents table
            $firstNameStmt = $dbh->prepare("SELECT agent_firstname AS firstname FROM agents WHERE user_id = :userId");
        } else {
            echo json_encode(['error' => 'Invalid user role']);
            exit();
        }

        // Bind the user ID to the SQL statement and execute it
        $firstNameStmt->bindParam(':userId', $userId);
        $firstNameStmt->execute();

        // Fetch the row from the database
        $firstNameRow = $firstNameStmt->fetch(PDO::FETCH_ASSOC);

        if ($firstNameRow) {
            // If a row was returned, output the first_name and user_id as a JSON object
            echo json_encode(['first_name' => $firstNameRow['firstname'], 'user_id' => $userId]);
        } else {
            // If no row was returned, the user ID was not found in the database
            echo json_encode(['error' => 'User not found']);
        }

    } else {
        // If no row was returned, the user ID was not found in the database
        echo json_encode(['error' => 'User not found']);
    }
} else {
    echo json_encode(['error' => 'Not logged in']);
}
?>
