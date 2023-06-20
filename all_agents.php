<?php
// Assuming you have a database connection established
include('db.php'); 
header('Content-Type: application/json');
// Fetch all agent data from the database
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM agents";
$result = $conn->query($sql);
$data = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "0 results";
}
echo json_encode($data);
echo $_SESSION["user_id"];
?>