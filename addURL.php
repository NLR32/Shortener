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
}


$long =  $json_obj['long'];
$short =  $json_obj['short'];
$username = $_SESSION['username'];




//check if already exists
$stmt = $mysqli->prepare("SELECT count(*) FROM urls WHERE short_url=?");


$stmt->bind_param('s', $short);



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



if ($count > 0){
    $stmt->close();
    

    $stmt = $mysqli->prepare("select auto_increment from information_schema.TABLES where TABLE_NAME ='urls' and TABLE_SCHEMA='shorten'");
    
    $stmt->execute();
    
  
    $stmt->bind_result($autoinc);
    
    $stmt->fetch();
    
    

    $short = $short . "_" . $autoinc;
    
    
}
$stmt->close();



//adding 
$stmt = $mysqli->prepare("INSERT INTO urls (short_url, user, full_url) VALUES (?,?,?)");


if (!$stmt) {

	echo json_encode(
		array(
			"success" => false,
			"message" => "prepare" . $mysqli->error
		)
	);
	exit;
}

if (!($stmt->bind_param('sss', $short, $username, $long))) {
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
		"message" => "url added"
	)
);



?>