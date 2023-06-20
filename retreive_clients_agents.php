<?php
session_start();

require('db.php');
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(array("error" => "User must be logged in."));
    exit;
}

$userId = $_SESSION['user_id'];

try {
    // Create a new PDO instance
    $dbh = new PDO('mysql:host=localhost;dbname=t3_alberto', 'admin232m', '6WO7hcod~Pn8^umu');

    // Get the client_id from the clients table
    $getClientId = $dbh->prepare("SELECT client_id FROM clients WHERE user_id = :userId");
    $getClientId->bindParam(':userId', $userId);
    $getClientId->execute();

    $clientIdRow = $getClientId->fetch(PDO::FETCH_ASSOC);
    $clientId = $clientIdRow['client_id'];

    // Get all agents
    $getAgents = $dbh->prepare("SELECT * FROM agents");
    $getAgents->execute();
    $agents = $getAgents->fetchAll(PDO::FETCH_ASSOC);

    // Prepare the checkMatch statement
    $checkMatch = $dbh->prepare("SELECT * FROM matches WHERE client_id = :clientId AND agent_id = :agentId");

    foreach ($agents as $index => $agent) {
        // Check if this agent has been hired by the client
        $checkMatch->bindParam(':clientId', $clientId);
        $checkMatch->bindParam(':agentId', $agent['agent_id']);
        $checkMatch->execute();
        $match = $checkMatch->fetch(PDO::FETCH_ASSOC);

        // Add a property to the agent array that tells whether the agent has been hired
        $agents[$index]['is_hired'] = $match !== false;
    }

    // Send the agent data to the client
    echo json_encode($agents);

} catch (Exception $e) {
    echo json_encode(array("error" => "Failed to get agent data: " . $e->getMessage()));
}
