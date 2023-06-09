<?php
session_start();

require('db.php');
header('Content-Type: application/json');

if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    $dbh = new PDO('mysql:host=localhost;dbname=t3_alberto', 'admin232m', '6WO7hcod~Pn8^umu');

    $getClientId = $dbh->prepare("SELECT client_id FROM clients WHERE user_id = :userId");
    $getClientId->bindParam(':userId', $userId);
    $getClientId->execute();
    
    $clientIdRow = $getClientId->fetch(PDO::FETCH_ASSOC);
    $clientId = $clientIdRow['client_id'];

    // Get all agent rows that the client has hired
    $getHiredAgents = $dbh->prepare("SELECT agents.* FROM matches INNER JOIN agents ON matches.agent_id = agents.agent_id WHERE matches.client_id = :clientId");
    $getHiredAgents->bindParam(':clientId', $clientId);
    $getHiredAgents->execute();

    $hiredAgents = $getHiredAgents->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($hiredAgents);
} else {
    echo json_encode([]);
}
?>
