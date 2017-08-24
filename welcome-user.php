<?php
session_start();
console_log($_SESSION["logged_in"]);
console_log($_SESSION["type"]);
console_log($_SESSION["email"]);
if (!isset($_SESSION["logged_in"])) {
  header("Location:home.php");
  exit;
}
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

$query = "SELECT first_name, middle_name, last_name, email, gender, date_of_birth, profile_url FROM user WHERE email = ?";
if($stmt = $conn->prepare($query)){
	console_log("Statement prepared");
	$email = $_SESSION["email"];
	$stmt->bind_param('s', $email);
	$stmt->execute();
	/* Store the result (to get properties) */
	$stmt->store_result();

	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	console_log($num_of_rows);
	/* Bind the result to variables */
	$stmt->bind_result($first_name, $middle_name, $last_name, $email, $gender, $date_of_birth, $profile_url);


echo '<html lang="en">
<head>
  <title>Welcome</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
  <p><h3>Welcome User</h3></p>
  <div class="row">
  <div class="col-md-10"></div>
  <div class="col-md-2">
  <a href="logout.php">Sign Out</a>
  </div>
  </div>         
  <table class="table table-striped">
    <thead>
      <tr>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Gender</th>
        <th>Date 0f Birth</th>
      </tr>
    </thead>
    <tbody>';
    while ($stmt->fetch()) {
  		echo "<tr><td>".$first_name."</td>";
  		echo "<td>".$middle_name."</td>";
  		echo "<td>".$last_name."</td>";
  		echo "<td>".$email."</td>";
  		echo "<td>".$gender."</td>";
  		echo "<td>".$date_of_birth."</td></tr>";
		}
    echo '</tbody></table><div class="row"><div class="col-md-4"><img style="width: 100px; height:100px;" src="'.$profile_url.'"/></div></div></div></body></html>';
}

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}
?>