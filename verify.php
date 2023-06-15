<?php 
    include('db.php'); //call to the connection file 
    
    $_SESSION['email'] = $_POST['email']; //initialize the email variable
    $_SESSION['password'] = $_POST['password']; //initialize the password variable
    
    $email = $_SESSION['email']; //set the email variable
    $pass = $_SESSION['password']; //set the password variable

    //inner join client and agent tables together



    $result = mysqli_query($conn, $query); //execute the query


    if ($result == false){ //if the query returns no rows, then the email and password are not in the database
        header("Location: query_error.html"); //redirect to the login page
    }

    if(mysqli_num_rows($result) > 0){ //if the query returns a row, then the email and password are in the database
        $row = mysqli_fetch_row($result);
        $_SESSION["uuid"] = $row[0];
        $_SESSION["role"] = $row[3];
       
        //header("Location: welcome.html"); //redirect to the index page
        header("Location: index.html");
    } else {
        
        header("Location: error_login.html"); //redirect to the login page
    }


?>