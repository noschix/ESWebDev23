<?php
session_start();

require('db.php');
header('Content-Type: application/json');

if(isset($_SESSION['user_id']) && isset($_POST['agent_id'])) {
    $userId = $_SESSION['user_id'];
    $agentId = $_POST['agent_id'];
    
    // Create a new PDO instance
    $dbh = new PDO('mysql:host=localhost;dbname=t3_alberto', 'admin232m', '6WO7hcod~Pn8^umu');
    
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
        $currentTimestamp = date('Y-m-d H:i:s');
        $insertMatch = $dbh->prepare("INSERT INTO matches (client_id, agent_id, created) VALUES (:clientId, :agentId, :created)");
        $insertMatch->bindParam(':clientId', $clientId);
        $insertMatch->bindParam(':agentId', $agentId);
        $insertMatch->bindParam(':created', $currentTimestamp);
        $insertMatch->execute();
        
        // If we made it here without an exception, commit the transaction
        $dbh->commit();
        echo json_encode(["message" => "Agent hired successfully."]);
    } catch (Exception $e) {
        // An error occurred; rollback the transaction
        $dbh->rollBack();
        echo json_encode(["message" => "Failed to hire agent: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["message" => "User must be logged in to hire an agent."]);
}
?>
