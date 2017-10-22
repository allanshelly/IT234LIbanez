<?php 
	session_start();
	header('Content-Type: application/json');
	
	define('DB_HOST', '127.0.0.1');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'inventory');

	$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	
	if(!$mysqli){
		die("Connection Failed: ".$mysqli->error);
	}
	$query = sprintf("SELECT total,date_purchased FROM sales GROUP BY date_purchased");
	$result = $mysqli->query($query);
	$data = array();
	foreach ($result as $row) {
		$data[] = $row;
	}

	$result->close();
	print json_encode($data);
?>