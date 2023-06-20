<?php

include 'db.php';

$email = $_POST['email'];
$firstname = $_POST['fname'];
$lastname = $_POST['lname'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
$role = $_POST["role"] == "agent" ? "agent" : "client"; 


$stmt = $conn->prepare("SELECT iduser, email FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if query failed
if ($result === false){
    header("Location: query_error.html");
    die("Query failed: " . mysqli_error($conn));
}

// Check if query failed
if($result->num_rows > 0){
    header("Location: user_exists.html"); 
    die("User already exists");
} else {
    //user table strucutre = iduser, email, password, role, registered
    $stmt = $conn->prepare("INSERT INTO `users` (`email`, `password`, `role`, `registered`) VALUES (?, ?, ?, current_timestamp())");
    $stmt->bind_param('sss', $email, $password, $role);
    $stmt->execute();

    // Check if query failed
    if($stmt === false){
        header("Location: query_error.html");
        die("Query failed: " . mysqli_error($conn));
    }

    // Get user id
    $user_id = $conn->insert_id;

    // Insert user into agent or client table
    if($role == "agent"){
        $agent_tel = $_POST['agent_tel'];
        $agent_city = $_POST['agent_city'];
        $agent_country = $_POST['agent_country'];
        $agent_website = $_POST['agent_website'];
        //agent table structure = user_id, agent_firstname, agent_lastname, agent_tel, agent_city, agent_country, agent_website
        $stmt = $conn->prepare("INSERT INTO `agent` (`user_id`, `agent_firstname`, `agent_lastname`, `agent_tel`, `agent_city`, `agent_country`, `agent_website`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('issssss', $user_id, $firstname, $lastname, $agent_tel, $agent_city, $agent_country, $agent_website);
    } else {
        //client table structure = user_id, client_firstname, client_lastname
        $stmt = $conn->prepare("INSERT INTO `client` (`user_id`, `client_firstname`, `client_lastname`) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $user_id, $firstname, $lastname);
    }
    
    $stmt->execute();

    // Check if query failed
    if($stmt === false){
        header("Location: query_error.html");
        die("Query failed: " . mysqli_error($conn));
    }

    $_SESSION["user_id"] = $user_id;
    $_SESSION["role"] = $role;

    if($role == "client"){
        header("Location: dashboard-client.html");
    } else {
        header("Location: dashboard-agent.html");
    }
}
?>
