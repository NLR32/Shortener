<?php
$mysqli = new mysqli('localhost', 'tamid', 'tamid', 'shorten');

if ($mysqli->connect_errno) {

	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>