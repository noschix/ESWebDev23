<?php

include 'db.php';

$email = $_POST['email'];
$firstname = $_POST['fname'];
$lastname = $_POST['lname'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
$role = $_POST["role"] == "agent" ? "agent" : "client"; 


$query = "SELECT iduser, email FROM users WHERE email = '$email'"; 
$result = mysqli_query($conn, $query);

// Check if query failed
if ($result == false){
    header("Location: query_error.html");
    die("Query failed: " . mysqli_error($conn));
}

// Check if user already exists
if(mysqli_num_rows($result) > 0){
    header("Location: user_exists.html"); 
   die("User already exists");
} else {

    // Insert user into database
    //user table strucutre = iduser, email, password, role, registered
    $query = "INSERT INTO `users` (`email`, `password`, `role`, `registered`) VALUES ('$email', '$password', '$role', current_timestamp())";

    $result = mysqli_query($conn, $query); 

    // Check if query failed
    if(!$result){
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
        $query = "INSERT INTO `agent` (`user_id`, `agent_firstname`, `agent_lastname`, `agent_tel`, `agent_city`, `agent_country`, `agent_website`) VALUES ('$user_id', '$firstname', '$lastname', '$agent_tel', '$agent_city', '$agent_country', '$agent_website')";
    } else {
        //client table structure = user_id, client_firstname, client_lastname
        $query = "INSERT INTO `client` (`user_id`, `client_firstname`, `client_lastname`) VALUES ('$user_id', '$firstname', '$lastname')";
    }
    
    $result2 = mysqli_query($conn, $query);

    // Check if query failed
    if(!$result2){
        header("Location: query_error.html");
        die("Query failed: " . mysqli_error($conn));
    }

    // Start session
    $_SESSION["user_id"] = $user_id;
    $_SESSION["role"] = $role;

    if($role == "client"){
        header("Location: dashboard-client.html");
    } else {
        header("Location: dashboard-agent.html");
    }
}

?>
