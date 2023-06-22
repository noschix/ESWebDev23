<?php
session_start();
header('Content-Type: application/json');

if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    //establish your db connection here
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE uuid = ?");
    $stmt->bind_param("s", $userId);

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
