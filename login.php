<?php
require 'database.php';
session_start();

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = htmlentities($json_obj['username']);
$password = htmlentities($json_obj['password']);
//This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)

$stmt = $mysqli->prepare("select count(*), password from users where username=?");

$stmt->bind_param('s', $username);

if (!$stmt) {

    echo json_encode(array(
		"success" => false,
		"message" => $mysqli->error
	));
}

$stmt->execute();

//bind it to variable count to store number of occurences of username
$stmt->bind_result($count, $passwordHash);

$stmt->fetch();
$stmt->close();

if ($count == 1) {
    if (password_verify($password, $passwordHash)) {
        //session_start();
        $_SESSION['username'] = $username;
       // $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); - DONT NAVIGATE TO OTHER PAGES DONT NEED TOKENS

        echo json_encode(array(
            "success" => true,
            "message" => $username
        ));
        exit;


    } else {
        echo json_encode(array(
            "success" => false,
            "message" => "Incorrect Password"
        ));
    }

} else{
    echo json_encode(array(
        "success" => false,
        "message" => "Username Doesnt Exist"
    ));
}


?>