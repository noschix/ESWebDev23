
<?php
session_start();
include('db.php'); //call to the connection file
require 'db.php'; // Replace with your file for DB connection
header('Content-Type: application/json');

if(isset($_SESSION['user_id']) && isset($_POST['agent_id'])) {
    $userId = $_SESSION['user_id'];
    $agentId = $_POST['agent_id'];
    
    // Create a new PDO instance
    $dbh = $conn;
    
    // Start a new transaction
    $dbh->beginTransaction();

    try {
        // First, get the client_id from the clients table
        $getClientId = $dbh->prepare("SELECT client_id FROM clients WHERE user_id = :userId");
        $getClientId->bindParam(':userId', $userId);
        $getClientId->execute();
        
        $clientIdRow = $getClientId->fetch(PDO::FETCH_ASSOC);
        $clientId = $clientIdRow['client_id'];

        // Now, insert the new relationship into the matches table
        $insertMatch = $dbh->prepare("INSERT INTO matches (client_id, agent_id) VALUES (:clientId, :agentId)");
        $insertMatch->bindParam(':clientId', $clientId);
        $insertMatch->bindParam(':agentId', $agentId);
        $insertMatch->execute();
        
        // If we made it here without an exception, commit the transaction
        $dbh->commit();
        echo "Agent hired successfully.";
    } catch (Exception $e) {
        // An error occurred; rollback the transaction
        $dbh->rollBack();
        echo "Failed to hire agent: " . $e->getMessage();
    }
} else {
    echo "User must be logged in to hire an agent.";
}
?>
