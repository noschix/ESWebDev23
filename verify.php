<?php 
    include('db.php'); //call to the connection file 
    
    $_SESSION['email'] = $_POST['email']; //initialize the email variable
    $_SESSION['password'] = $_POST['password']; //initialize the password variable
    
    $email = $_SESSION['email']; //set the email variable
    $password = $_SESSION['password']; //set the password variable

    //select the user from the database
    $query = "SELECT * FROM users WHERE email = '$email'"; //query to check if the email is in the database
    $result = mysqli_query($conn, $query); //execute the query

    if ($result == false){ //if the query returns no rows, then the email and password are not in the database
        header("Location: query_error.html"); //redirect to the login page
    }

    if(mysqli_num_rows($result) > 0){ //if the query returns a row, then the email is in the database
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) { // Verifying the password
            //uuid, email, password, role, registerdate
            $_SESSION["uuid"] = $row["uuid"];
            $_SESSION["role"] = $row["role"]; 
        
            header("Location: dashboard-client.html"); //redirect to the index page
        } else {
            header("Location: error_login.html"); //redirect to the login page
        }
    } else {
        header("Location: error_login.html"); //redirect to the login page
    }
?>
