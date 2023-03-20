<?php
require 'database.php';
session_start();
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');

//This will store the data into an associative array
$json_obj = json_decode($json_str, true);


if (!isset($_SESSION['username'])){
    echo json_encode(array(
		"success" => false,
		"message" => "user is not logged in"
	));
    exit;
}


$id =  $json_obj['urlid'];
$username = $_SESSION['username'];
$full =  $json_obj['full'];
$short =  $json_obj['short'];





$stmt = $mysqli->prepare("UPDATE urls SET full_url=?, short_url =? WHERE urlid=? AND user=?");


if (!$stmt) {

	echo json_encode(
		array(
			"success" => false,
			"message" => "prepare" .$mysqli->error
		)
	);
	exit;
}

if (!($stmt->bind_param('ssis', $full, $short, $id, $username))) {
	echo json_encode(
		array(
			"success" => false,
			"message" => "bind" 
		)
	);
	exit;
}


if (!$stmt->execute()) {
   
	echo json_encode(
		array(
			"success" => false,
			"message" => "execute"
		)
	);
	exit;
} 


if (!$stmt->close()){
	echo json_encode(
		array(
			"success" => false,
			"message" => "close"
		)
	);
	exit;
}


echo json_encode(
	array(
		"success" => true,
		"message" => "url changed"
	)
);


?>