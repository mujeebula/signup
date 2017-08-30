<?php
$servername = "localhost";
$username = "root";
$password = "mindfire";
$database = "phpAssignment";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} 
console_log("Connected successfully");

$query = "SELECT first_name, middle_name, last_name, email, gender, date_of_birth FROM user WHERE email = ?";
if($stmt = $conn->prepare($query)){
	console_log("Statement prepared");
	$stmt->bind_param('s','iammujeebul@gmail.com');
	$stmt->execute();
	/* Store the result (to get properties) */
	$stmt->store_result();

	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	console_log($num_of_rows);
	/* Bind the result to variables */
	$stmt->bind_result($first_name, $middle_name, $last_name, $email, $gender, $date_of_birth);

	while ($stmt->fetch()) {
		echo "<tr><td>".$first_name."</td>";
		echo "<td>".$middle_name."</td>";
		echo "<td>".$last_name."</td>";
		echo "<td>".$email."</td>";
		echo "<td>".$gender."</td>";
		echo "<td>".$date_of_birth."</td></tr>";
	}
?>