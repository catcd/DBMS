<?php

$servername = "localhost";
$username = "root";
$password = "36800149";
$dbname = "employees";

//Connect to MYSQL server
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}


function execute_queries($query){
	global $conn;

	$complete = false;

	if ($conn->multi_query($query))
	{
		do
		{
			// Store first result set
			if ($result = $conn->store_result()) {
				// Fetch one and one row
				while ($row = $result->fetch_row()){ echo ""; }
				// Free result set
				$result->free();
			}
		}
		while ($conn->more_results() && $conn->next_result());

		$complete = true;
	}

	return $complete;
}

?>