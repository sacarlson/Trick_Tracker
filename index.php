<?php
// Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->
  //header('Content-type: text/html');
  header('Access-Control-Allow-Origin: *');

 include('config.php');
 
 $id = $_GET['id'];
 $timestamp = $_GET['timestamp'];
 $lat = $_GET['lat'];
 $lon = $_GET['lon'];
 $speed = $_GET['speed'];
 $bearing = $_GET['bearing'];
 $altitude = $_GET['altitude'];
 if (empty( $_GET['batt'])) {
   $batt = "0";
 } else {
   $batt = $_GET['batt'];
 }
 if (empty( $_GET['type'])) {
   $type = "0";
 } else {
   $type = $_GET['type'];
 }
 date_default_timezone_set('Asia/Bangkok');
 $time = date("F j, Y, g:i a");
 
 if (strlen( $_GET['speed']) == 0) {
   $speed = "0";
 }
 if (strlen( $_GET['bearing']) == 0) {
   $bearing = "0";
 }
 if (strlen( $_GET['altitude']) == 0) {
   $altitude = "0";
 }

 if (strlen( $_GET['id']) == 0) {
   $id = "0";
 }


function logsession() {
 global $datetime, $id, $timestamp, $lat, $lon, $speed, $bearing, $altitude, $status,$type,$batt;
 //date_default_timezone_set('Asia/Bangkok');
 $datetime = date("F j, Y, g:i a");
 $outString =  $datetime . " : " . $id . " : " . $timestamp . " : ". $lat ." : ". $lon . " : " . $speed . " : " . $bearing . " : " . $altitude  . " : " . $batt . " : ".  $status . " : " . $type . "\n";
 $f = fopen("./session_track.log", "a");
 fwrite( $f, $outString );
 fclose( $f );
 return;
 }

 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sql = "INSERT INTO data (id, timestamp, lat,lon,speed,bearing,altitude,type)
VALUES ( $id, $timestamp, $lat, $lon, $speed, $bearing,$altitude,$type)";

//$sql2 = "UPDATE users SET last_lat=$lat, last_lon=$lon, type=$type, timestamp=$timestamp WHERE traccar_id=$id"; 
$sql2 = "UPDATE users SET last_lat=$lat, last_lon=$lon, timestamp=$timestamp WHERE traccar_id=$id"; 

//$sql = "DELETE FROM data";

if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
    $status2 = " record success";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
    $status2 = "Error: " . $conn->error;
}

if ($conn->query($sql2) === TRUE) {
    //echo "New record created successfully";
    $status = " record success";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
    $status = "Error: " . $conn->error;
    //$status = $sql;
}
 //echo "ok";
//$status = $sql;
$conn->close();
logsession();
?>
