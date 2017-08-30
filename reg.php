<?php
require 'mailer/PHPMailerAutoload.php';
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
    	console_log("Not post");
}

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
            header("Location:verify.html");
            exit;
         }
    } else {
        console_log("Error: " . "<br />" . $conn->error);
}

$conn->close();

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}

?>