<?php
session_start();
console_log($_SESSION["logged_in"]);
console_log($_SESSION["type"]);
if (isset($_SESSION["logged_in"]) && $_SESSION["type"] == 0) {
  header("Location:welcome-user.php");
  exit;
}else if(isset($_SESSION["logged_in"]) && $_SESSION["type"] == 1){
  header("Location:welcome-admin.php");
  exit;
}
function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login Here</title>
	<meta charset="utf-8">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
	<link rel="stylesheet" type="text/css" href="./myStyle.css">
 </head>

 <body>
 	
 	<div class="container-fluid">
 		
		<div class="page-header">
			<h3 class="text-primary">Login Here</h3>
		</div>
	 	
    <form action="login.php" method="post" id="signupForm" onsubmit="return validateLogin()" class="form jumbotron">
      
      <div class="form-group row">
        <div class="col-md-2 vertical-center">
          <label for="email">Email</label>
            </div>
            <div class="col-md-2 vertical-center">
              <input class="form-control to-left" id="email" name="email" type="email" required>
            </div>
      </div>
      <div class="form-group row">
        <div class="col-md-2 vertical-center">
          <label for="pwd">Password</label>
            </div>
            <div class="col-md-2 vertical-center">
              <input class="form-control to-left" id="pwd" name="pwd" type="password" required>
            </div>
            <div class="col-md-4">
              <span id="err-pwd" class="error text-danger" hidden></span>
            </div>
      </div>
      
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10 to-left">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
      </div>
    </form>
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