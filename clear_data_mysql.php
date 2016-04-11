<?php
// Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->
// Danger!!! this will delete the whole database contents, used to setup a clean db
echo "start clear mysql";


include('config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "DELETE FROM `data`;";
$sql2 = "DELETE FROM `user`;";
if ($conn->query($sql) === TRUE) {
     echo "Table data cleared successfully <br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
}

//if ($conn->query($sql2) === TRUE) {
//    echo "Table user cleared successfully";
//    $status = " record success";
//} else {
 //   echo "Error: " . $sql2 . "<br>" . $conn->error;

//}

echo "done <br>";

?>
