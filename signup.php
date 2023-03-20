<?php
require 'database.php';
session_start();

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $json_obj['username'];
$password = password_hash($json_obj['password'], PASSWORD_BCRYPT);
//This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)

$stmt = $mysqli->prepare("select count(*) from users where username=?");

    $stmt->bind_param('s', $username);

    if (!$stmt) {

        echo json_encode(array(
            "success" => false,
            "message" => $mysqli->error
        ));
    }

    $stmt->execute();

    //bind it to variable count to store number of occurences of username
    $stmt->bind_result($count);

    $stmt->fetch();
    $stmt->close();
   
    if ($count >= 1) {
        echo json_encode(array(
            "success" => false,
            "message" => "username already exists",
            "value of count" => $count
        ));
    }else if ($count == 0) {
        //actually add user to data base
        

        $stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
        if (!$stmt) {
            echo json_encode(array(
                "success" => false,
                "message" => $mysqli->error
            ));
            exit;
        }
       
        
        $stmt->bind_param('ss', $username, $password);

        $stmt->execute();

        $stmt->close();
        echo json_encode(array(
            "success" => true
        ));
    }




?>