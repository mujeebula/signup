<?php
require 'mailer/PHPMailerAutoload.php';
session_start();
if (isset($_SESSION["logged_in"]) && isset($_SESSION["email"]) && $_SESSION["type"] == 0) {
	console_log($_SESSION["logged_in"]);
	console_log($_SESSION["type"]);
	console_log($_SESSION["email"]);
  	header("Location:welcome-user.php");
  	exit;
}else if(isset($_SESSION["logged_in"]) && isset($_SESSION["email"]) && $_SESSION["type"] == 1){
	console_log($_SESSION["logged_in"]);
	console_log($_SESSION["type"]);
	console_log($_SESSION["email"]);
	header("Location:welcome-admin.php");
  	exit;
}

$login_err;
$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$parts = parse_url($url);
parse_str($parts['query'], $query);
console_log("action = ".$query['action']);

if($_SERVER["REQUEST_METHOD"] == "POST" && $query['action'] == "reg"){
	console_log("received".$query["action"]);
	
	$valid_email = false;
	if (check_email($_POST["email"]) === TRUE) {
		$valid_email = true;
	    $target_dir = "uploads/";
	    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
	    $uploadOk = 1;
	    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	    // Check if image file is a actual image or fake image
	    if(isset($_POST["submit"])) {
	        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
	        if($check !== false) {
	            console_log("File is an image - " . $check["mime"] . ".");
	            $uploadOk = 1;
	        } else {
	            console_log("File is not an image.");
	            $uploadOk = 0;
	        }
	    }
	    // Check file size
	    if ($_FILES["profile_picture"]["size"] > 500000) {
	        console_log("Sorry, your file is too large.");
	        $uploadOk = 0;
	    }
	    // Allow certain file formats
	    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	    && $imageFileType != "gif" ) {
	        console_log("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
	        $uploadOk = 0;
	    }
	    // Check if $uploadOk is set to 0 by an error
	    if ($uploadOk == 0) {
	        console_log("Sorry, your file was not uploaded.");
	    // if everything is ok, try to upload file
	    } else {
	        $date = new DateTime();
	        $ts = $date->getTimestamp();
	        $target_file = $target_dir.$ts."-".basename($_FILES["profile_picture"]["name"]);
	        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
	            console_log("The file ". basename( $_FILES["profile_picture"]["name"]). " has been uploaded.");
	        } else {
	            console_log("Sorry, there was an error uploading your file.");
	        }
	    }
	    $first_name = $_POST["first_name"];
	    $middle_name = $_POST["middle_name"];
	    $last_name = $_POST["last_name"];
	    $email = $_POST["email"];
	    $password = $_POST["pwd"];
	    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
	    $gender = $_POST["gender"];
	    $date_of_birth = $_POST["date_of_birth"];
	    $profile_url = $target_file;
	    $hash = md5( rand(0,1000) );
	    $active = '0';
	    console_log($first_name.$middle_name.$last_name.$email.$password.$gender.$date_of_birth.$profile_url.$hash.$active);
	}else{
		$signup_email_error = "Email already registered.";
	}


	if($valid_email){
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
		$statement = $conn->prepare("INSERT INTO user (first_name, middle_name, last_name, email, password, gender, date_of_birth, profile_url, hash, active)
		        VALUES (?,?,?,?,?,?,?,?,?,?)");

		$statement->bind_param("ssssssssss",$first_name, $middle_name, $last_name, $email, $hashedPassword, $gender, $date_of_birth, $profile_url, $hash, $active);

		if ($statement->execute() === TRUE) {
		        console_log("New record created successfully");
		        $mail = new PHPMailer(); // create a new object
		        $mail->IsSMTP(); // enable SMTP
		        //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		        $mail->SMTPAuth = true; // authentication enabled
		        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		        $mail->Host = "smtp.gmail.com";
		        $mail->Port = 465; // or 587
		        $mail->IsHTML(true);
		        $mail->Username = "mujeeb68022@gmail.com";
		        $mail->Password = "qwerty54321";
		        $mail->SetFrom("mujeeb68022@gmail.com");
		        $mail->Body = 'http://localhost/signupPage/verify.php?email='.$email.'&hash='.$hash;
		        $mail->AddAddress($email);

				if(!$mail->Send()) {
					console_log("Mailer Error: " . $mail->ErrorInfo);
				} else {
					console_log("Message has been sent");
					header("Location:signup-done.php");
					exit;
				}
	    } else {
	        console_log("Error: " . $conn->error);
		}
		$conn->close();
	}

}else if($_SERVER["REQUEST_METHOD"] == "POST" && $query['action'] == "login"){

	console_log("received ".$query['action']);

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
	   	if ($num_of_rows == 0) {
	   		$login_err = "Invalid email";
	   	}
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
	    	}else {
	      		// Invalid credentials
	      		$login_err = "Invalid password";		
	    	}
	   	}
		/* free results */
		$stmt->free_result();

		/* close statement */
		$stmt->close();
	}
}
function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .');';
  echo '</script>';
}

function check_email($email){
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
	console_log("Check email:".$email);
	$query = "SELECT id FROM user WHERE email = ?";
	if($stmt = $conn->prepare($query)){
		console_log("Statement prepared");
	   	$stmt->bind_param('s',$email);
	   	$stmt->execute();
	   	/* Store the result (to get properties) */
	   	$stmt->store_result();

	   	/* Get the number of rows */
	   	$num_of_rows = $stmt->num_rows;
	   	if ($num_of_rows == 0) {
	   		console_log("valid email");
	   		$stmt->close();
	   		$conn->close();
	   		return true;
	   	}
	}
	console_log("Invalid email");
	$conn->close();
	return false;
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Profile App</title>
	<meta charset="utf-8">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
	<link rel="stylesheet" type="text/css" href="./myStyle.css">
 </head>

 <body>
 	
 	<div class="container-fluid login-background">

 <div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Profile App</a>
        </div>
        <center>
            <div class="navbar-collapse collapse" id="navbar-main">
                <ul class="nav navbar-nav">
                    <!--li class="active"><a href="#">Link</a>
                    </li-->
                    
                </ul>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?action=login';?>" method="post" id="signupForm" onsubmit="return validateLoginForm()" class="navbar-form navbar-right" role="search">
                    	<label id="login-err-pwd" class="error text-danger"><?php echo $login_err ?></label>
                    <div class="form-group">
                        <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="login-pwd" placeholder="Password" name="pwd" type="password" required>
                    </div>
                    <button type="submit" class="btn btn-default">Sign In</button>
                </form>
            </div>
        </center>
    </div>
</div>

 		<div class="row to-bottom">
			<div class="col-md-4">
				<h2>
					Create an Account
				</h2>
			</div>
			<div class="col-md-8"></div>

 		</div>
	 	 		<div class="row"><hr class="divider"/></div>

	 	<div>
	 		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?action=reg';?>" method="post" id="signupForm" enctype="multipart/form-data" onsubmit="return validateSignupForm()" class="form">
	 			<div class="form-group row">
	 				<div class="col-md-2 vertical-center">
						<label for="firstName">First Name</label>
		 			</div>
	 				<div class="col-md-2 vertical-center">
		   				<input class="form-control to-left" name="first_name" id="firstName" type="text" required>
	 				</div>
	 				<div class="col-md-2 vertical-center to-left">
						<label id="label-middleName" for="middleName">Middle Name</label>
	 				</div>
	 				<div id="middle-name" class="col-md-2 vertical-center">
		   				<input class="form-control" name="middle_name" id="middleName" type="text">
	 				</div>
	 				<div class="col-md-2 vertical-center to-left">
	 					<label id="label-lastName" for="lastName">Last Name</label>
	 				</div>
	 				<div class="col-md-2 vertical-center">
	 					<input class="form-control" name="last_name" id="lastName" type="text" required>
	 				</div>
	 			</div>
	 			<div class="form-group row">
	 				<div class="col-md-2 vertical-center">
		 				<label for="email">Email</label>
	      			</div>
	      			<div class="col-md-2 vertical-center">
	      				<input class="form-control to-left" name="email" id="email" type="email" required>
	      			</div>
	      			<div class="col-md-4">
	      				<span class="error text-danger"><?php echo $signup_email_error ?></span>
	      			</div>
	 			</div>
	 			<div class="form-group row">
	 				<div class="col-md-2 vertical-center">
		 				<label for="pwd">Password</label>
	      			</div>
	      			<div class="col-md-2 vertical-center">
	      				<input class="form-control to-left" value="Qwerty@123" name="pwd" id="signup-pwd" type="password" required>
	      			</div>
	      			<div class="col-md-4">
	      				<span id="signup-err-pwd" class="error text-danger" hidden></span>
	      			</div>
	 			</div>
	 			<div class="form-group row">
	 				<div class="col-md-2 vertical-center">
						<label for="repeat-pwd">Confirm Password</label>
	 				</div>
	 				<div class="col-md-2 vertical-center">
	 					<input class="form-control to-left" value="Qwerty@123" name="repeat-pwd" id="signup-repeat-pwd" type="password" required>
	 				</div>
	 				<div class="col-md-4">
	      				<span id="signup-err-repeat-pwd" class="error text-danger" hidden></span>
	      			</div>
	 			</div>

	 			<div class="form-group row">
	 			 	<div class="col-md-2 vertical-center">
	 			 		<label>Gender</label>
	 			 	</div>
	 				<div class="col-md-2 vertical-center to-left">
	 					<label class="radio-inline"><input type="radio" name="gender" value="male" required/>Male</label>
						<label class="radio-inline"><input type="radio" name="gender" value="female" />Female</label>
					</div>
	 			</div>

	 			<div class="form-group row">
	 				<div class="col-md-2 vertical-center">
	        			<label class="control-label" for="date">Date of Birth</label>
	        		</div>
	        		<div class="col-md-2 vertical-center">
	        			<input class="form-control to-left" id="dateOfBirth" name="date_of_birth" placeholder="yyyy-mm-dd" type="text" required/>
	        		</div>
	 			</div>

	 			<div class="form-group row">
	 				<div class="col-md-2 vertical-center">
	 				<label class="control-label" for="profile-picture">Profile Picture</label>
	        		</div>
	        		<div class="col-md-2 vertical-center">
						<input id="profile-picture" name="profile_picture" class="to-left" type="file" required>
	        		</div>
	 			</div>
	 			<div class="row">
					<div class="col-md-2"></div>
		 			<div class="col-md-10 to-left">
		 				<button type="submit" class="btn btn-primary">Signup</button>
		 			</div>
		 		</div>
	 		</form>
 		</div>
 	</div>
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<!-- Bootstrap Date-Picker Plugin -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
 	<script type="text/javascript" src="./myScript.js"></script>
 </body>

 </html>