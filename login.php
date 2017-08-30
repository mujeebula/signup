<?php
session_start();
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = $_POST["email"];
    $password = $_POST["pwd"];
    console_log($email.$password);
}else{
	die("Error");
}
$query = "SELECT password, account_type FROM user WHERE email = ?";
if($stmt = $conn->prepare($query)){
	 console_log("Statement prepared");
   	$stmt->bind_param('s',$email);
   	$stmt->execute();
   	/* Store the result (to get properties) */
   	$stmt->store_result();

   	/* Get the number of rows */
   	$num_of_rows = $stmt->num_rows;
   	console_log($num_of_rows);
   	/* Bind the result to variables */
   	$stmt->bind_result($hash, $account_type);

   	while ($stmt->fetch()) {
		  console_log($hash);
    	if (password_verify($password, $hash)) {
    		console_log("Correct");
        console_log($account_type);
        if($account_type == 0){
          $_SESSION["email"] = $email;
          $_SESSION["logged_in"] = "in";
          $_SESSION["type"] = $account_type;
          header("Location:welcome-user.php");
          exit;
        }elseif ($account_type == 1) {
          $_SESSION["email"] = $email;
          $_SESSION["logged_in"] = "in";
          $_SESSION["type"] = $account_type;
          header("Location:welcome-admin.php");
          exit;
        }
    	}
    	else {
      		// Invalid credentials
      		console_log("in Correct");	
    	}
   	}
	/* free results */
	$stmt->free_result();

	/* close statement */
	$stmt->close();
}


function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}

?>