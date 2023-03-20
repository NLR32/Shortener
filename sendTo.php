<?php
require 'database.php';

$loc = $_GET['loc'];
if (isset($_GET['loc'])){
    echo $loc;

    $stmt = $mysqli->prepare("select full_url from urls where short_url=?");

    if (!$stmt){
        print($mysqli->error);
    }

    $stmt->bind_param('s', $loc);
    if (!$stmt){
        print($mysqli->error);
    }

    $stmt->execute();
    if (!$stmt){
        print($mysqli->error);
    }

    //bind it to variable count to store number of occurences of username
    $stmt->bind_result($full);
    if (!$stmt){
        print($mysqli->error);
    }

    $stmt->fetch();
    if (!$stmt){
        print($mysqli->error);
    }
    $stmt->close();

    header("Location: $full");

} else{
   header("Location: notReal.html");
}

?>