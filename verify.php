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


    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    // Verify data
    $email = mysql_escape_string($_GET['email']); // Set email variable
    $hash = mysql_escape_string($_GET['hash']); // Set hash variable
     console_log($email);
     console_log($hash);
    $sql = "SELECT email, hash, active FROM user WHERE email='".$email."' AND hash='".$hash."' AND active='0'";
    console_log($sql);
    $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            $sql = "UPDATE user SET active='1' WHERE email='".$email."' AND hash='".$hash."' AND active='0'";
            console_log($sql);
            
            if ($conn->query($sql) === TRUE) {
                echo '<div class="statusmsg">Your account has been activated, you can now login</div>';
                header("refresh:3,Location:home.php");
                exit;
            }else{
            // Invalid approach
            echo '<div class="statusmsg">Invalid approach, please use the link that has been send to your email.</div>';
            }
        } else {
            echo "0 results";
        }
        $conn->close();
    }

    function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}
?>