<?php

include 'db.php';

$email = $_POST['email'];
$firstname = $_POST['fname'];
$lastname = $_POST['lname'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
$role = $_POST["role"] == "agent" ? "agent" : "client"; 


$query = "SELECT * FROM users WHERE email = '$email'"; 
$result = mysqli_query($conn, $query);

// Check if query failed
if ($result == false){
    header("Location: query_error.html");
    echo $query;
    die("Query failed: " . mysqli_error($conn));
}

// Check if user already exists
if(mysqli_num_rows($result) > 0){
    header("Location: user_exists.html"); 
   die("User already exists");
} else {

    // Insert user into database
    //user table strucutre = iduser, email, password, role, registered
    $stmt = $conn->prepare("INSERT INTO `users` (`uuid`,`email`, `password`, `role`, `registered`) VALUES (NULL, ?, ?, ?, current_timestamp())");
    $stmt->bind_param("sss", $email, $password, $role);

    if($stmt->execute()) {
        // Get user id
        $user_id = $conn->insert_id;

        // Insert user into agent or client table
        if($role == "agent"){
            $agent_about = $_POST['agent_about'];
            $agent_city = $_POST['agent_city'];
            $agent_country = $_POST['agent_country'];
            $agent_website = $_POST['agent_website'];
            $agent_exp = $_POST['agent_exp'];

            $stmt2 = $conn->prepare("INSERT INTO `agents` (`agent_id`,`user_id`, `agent_firstname`, `agent_lastname`,`agent_exp`,`agent_about`, `agent_city`, `agent_country`, `agent_website`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("isssssss", $user_id, $firstname, $lastname, $agent_exp, $agent_about, $agent_city, $agent_country, $agent_website);
            $stmt2->execute();
        } else {
            $stmt2 = $conn->prepare("INSERT INTO `clients` (`client_id`,`user_id`, `client_firstname`, `client_lastname`) VALUES (NULL, ?, ?, ?)");
            $stmt2->bind_param("iss", $user_id, $firstname, $lastname);
            $stmt2->execute();
        }

        // Start session
        $_SESSION["user_id"] = $user_id;
        $_SESSION["role"] = $role;

        if($role == "client"){
            header("Location: dashboard-client.html");
        } else {
            header("Location: dashboard-agent.html");
        }

    } else {
        header("Location: query_error.html");
        die("Query failed: " . $conn->error);
    }
}

?>
