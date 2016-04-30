<?php
// Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->
  //header('Content-type: text/html'); 
  header('Access-Control-Allow-Origin: *'); 
  header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');

 include('config.php');
 
 $id = $_GET['id'];
 $timestamp = $_GET['timestamp'];
 $lat = $_GET['lat'];
 $lon = $_GET['lon'];
 //$speed = $_GET['speed'];
 //$bearing = $_GET['bearing'];
 //$altitude = $_GET['altitude'];
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
 
 if (empty( $_GET['speed'])) {
   $speed = "0";
 } else {
   $speed = $_GET['speed'];
 }
 if (empty( $_GET['bearing'])) {
   $bearing = "0";
 } else {
   $bearing = $_GET['bearing'];
 }
 if (empty( $_GET['altitude'])) {
   $altitude = "0";
 } else {
   $altitude = $_GET['altitude'];
 }

 if (empty($_GET['id']) || (strlen( $_GET['id']) == 0)) {
   return;
 }

 // Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 check_id_user_exists();
 insert_data(); 
 $conn->close();
 logsession();

function logsession() {
 global $datetime, $id, $timestamp, $lat, $lon, $speed, $bearing, $altitude, $status,$type,$batt;
 //date_default_timezone_set('Asia/Bangkok');
 $datetime = date("F j, Y, g:i a");
 $outString =  $datetime . " : " . $id . " : " . $timestamp . " : ". $lat ." : ". $lon . " : " . $speed . " : " . $bearing . " : " . $altitude  . " : " . $batt . " : ".  $status . " : " . $type . "\n";
 wrt_log( $outString );
 return;
 }

 function wrt_log( $string) {
   $f = fopen("./session_track.log", "a");
   fwrite( $f, $string );
   fclose( $f );
   return;
 }  

 

function check_id_user_exists() {
  global $datetime, $id, $timestamp, $lat, $lon, $speed, $bearing, $altitude, $status,$type,$batt, $conn;
  
  if (strlen($id) < 6 ){
    wrt_log("id was too short to add to user table");
    return;
  }
  $sql = "SELECT * FROM `users` WHERE id = ". $_GET['id'];

  if(!$result = $conn->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
  }
 
  if ($result->num_rows > 0) {
    wrt_log("user exists: " . $id );
  } else {
    wrt_log("add user: " . $id);
    if ($type == 0) {
      $type2 = 12;
    } else {
      $type2 = $type;
    }
    $sql = "INSERT INTO users (id, username, password, timestamp,type) VALUES ( $id, $id, $id , $timestamp, $type2)";
    $conn->query($sql);
  }
}

function insert_data() {
  global $datetime, $id, $timestamp, $lat, $lon, $speed, $bearing, $altitude, $status,$type,$batt, $conn;  
  
  if ($type > 1000) {    
    $type2 = $type - 1000;
    $type = 0;
    $sql2 = "UPDATE users SET lat=$lat, lon=$lon, timestamp=$timestamp, type=$type2 WHERE id=$id"; 
  } else {
    $sql2 = "UPDATE users SET lat=$lat, lon=$lon, timestamp=$timestamp WHERE id=$id"; 
  }

  //$sql2 = "UPDATE users SET lat=$lat, lon=$lon, timestamp=$timestamp, type=$type2 WHERE id=$id"; 


  $sql = $sql = "INSERT INTO data (id, timestamp, lat,lon,speed,bearing,altitude,type)
  VALUES ( $id, $timestamp, $lat, $lon, $speed, $bearing,$altitude, $type)";
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
}



?>
