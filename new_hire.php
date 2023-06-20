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

        // Check if the client has already hired the agent
        $checkMatch = $dbh->prepare("SELECT * FROM matches WHERE client_id = :clientId AND agent_id = :agentId");
        $checkMatch->bindParam(':clientId', $clientId);
        $checkMatch->bindParam(':agentId', $agentId);
        $checkMatch->execute();

        // If a match is found, unhire the agent by deleting the match record
        if ($checkMatch->rowCount() > 0) {
            $deleteMatch = $dbh->prepare("DELETE FROM matches WHERE client_id = :clientId AND agent_id = :agentId");
            $deleteMatch->bindParam(':clientId', $clientId);
            $deleteMatch->bindParam(':agentId', $agentId);
            $deleteMatch->execute();
            echo json_encode(["message" => "Agent unhired successfully."]);
        } else {
            // If no match is found, hire the agent by inserting a new match record
            $currentTimestamp = date('Y-m-d H:i:s');
            $insertMatch = $dbh->prepare("INSERT INTO matches (client_id, agent_id, created) VALUES (:clientId, :agentId, :created)");
            $insertMatch->bindParam(':clientId', $clientId);
            $insertMatch->bindParam(':agentId', $agentId);
            $insertMatch->bindParam(':created', $currentTimestamp);
            $insertMatch->execute();
            echo json_encode(["message" => "Agent hired successfully."]);
        }

        // If we made it here without an exception, commit the transaction
        $dbh->commit();
    } catch (Exception $e) {
        // An error occurred; rollback the transaction
        $dbh->rollBack();
        echo json_encode(["message" => "Failed to hire/unhire agent: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["message" => "User must be logged in to hire/unhire an agent."]);
}
?>
