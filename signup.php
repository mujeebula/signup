<?php 
$servername = "localhost";
$username = "root";
$password = "mindfire";
$database = "phpAssignment";
$first_name = $middle_name = $last_name = $password = $gender = $date_of_birth = $email = "";
$target_dir = "uploads/";
$target_file = $target_dir.basename($_FILES["profile_picture"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["profile_picture"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        //echo "The file ". basename( $_FILES["profile_picture"]["name"]). " has been uploaded.";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $first_name = check_input($_POST["first_name"]);
            $middle_name = check_input($_POST["middle_name"]);
            $last_name = check_input($_POST["last_name"]);
            $email = check_input($_POST["email"]);
            $password = $_POST["pwd"];
            $gender = check_input($_POST["gender"]);
            $date_of_birth = check_input($_POST["date_of_birth"]);
            $profile_url = "/uploads/".$target_file;
            // Create connection
            $conn = new mysqli($servername, $username, $password, $database);
            // Check connection
            if ($conn->connect_error) {
                console_log("Fail to connect...");
                die("Connection failed: ".$conn->connect_error);
            }else{
                console_log("Connected");
            }
            $sql = "INSERT INTO user (first_name, middle_name, last_name, email, password, gender, date_of_birth, profile_url)
            VALUES ($first_name, $middle_name, $last_name, $email, $password, $gender, $data_of_birth, $profile_url)";
            console_log($sql);
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br />" . $conn->error;
            }
        }else{
            echo "Not post";
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

function check_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}


function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}

?>