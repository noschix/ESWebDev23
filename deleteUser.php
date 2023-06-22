<?php
session_start();

include('db.php'); 
header('Content-Type: application/json');

if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    //establish your db connection here
    $connNew = $conn;
    
    if ($connNew->connect_error) {
        die(json_encode(['error' => 'Connection failed: ' . $connNew->connect_error]));
    }

    $stmt = $connNew->prepare("DELETE FROM users WHERE uuid = ?");
    $stmt->bind_param("i", $userId); 

    if($stmt->execute()){
        session_unset();
        session_destroy();
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['error' => 'Error deleting account.']);
    }
} else {
    echo json_encode(['error' => 'Not logged in']);
}
?>
