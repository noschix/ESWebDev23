<?php

include 'db.php';

$email = $_POST['email'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$password = $_POST['password'];

if (empty($_POST["role"])){
    $admin = 0;
} else {
    $admin = 1;
}

$query = "SELECT iduser, email FROM users WHERE email = '$email'"; //check for user 
$result = mysqli_query($conn, $query); //execute the query


if ($result == false){ //if the query returns no rows, then the email and password are not in the database
    die("Query failed: " . mysqli_error($conn));
    header("Location: query_error.html"); //redirect to the login page
}

if(mysqli_num_rows($result) > 0){ //if the query returns a row, then the email and password are in the database
   die("User already exists");
    header("Location: user_exists.html"); //redirect to the login page

} else {
    //Nouser exists
    $query = "INSERT INTO users (email, firstname, lastname, password, admin) VALUES ('$email', '$firstname', '$lastname', '$pass', '$admin')";
    $result = mysqli_query($conn, $query); //execute the query

    if(!$result){
        die("Query failed: " . mysqli_error($conn));
        header("Location: query_error.html"); //redirect to the login page
    }
    $_SESSION["id_user"] = $conn->insert_id;
    $_SESSION["admin"] = $admin;
    header("Location: welcome.html"); //redirect to the index page

}


?>
