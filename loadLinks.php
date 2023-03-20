
<?php
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);


require 'database.php';
session_start();







$username = $_SESSION['username'];



//Variables can be accessed as such:


//This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)

$stmt = $mysqli->prepare("SELECT short_url, full_url , URLid from urls where user = ?");



if (!$stmt) {

    echo json_encode(
        array(
            "success" => false,
            "message" => "prepare"
        )
    );
    exit;
}


if (!($stmt->bind_param('s', $username))) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "param"
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


$stmt->bind_result($short, $full, $id);
if (!$stmt) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "result"
        )
    );
    exit;
}


$urls = [];
while ($stmt->fetch()) {


    $temp = array(
        'short' => $short,
        'full' => $full,
        'id' => $id
      
    );
    array_push($urls, $temp);

}


echo json_encode(
    array(
        "success" => true,
        "urls" => $urls
    )
);

$stmt->close();


?>