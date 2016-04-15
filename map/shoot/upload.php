<?php 

  // be aware of file / directory permissions on your server 
  //header('Access-Control-Allow-Origin: *'); 
  //header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');

  //include('config.php');
 
 $id = $_GET['id'];
 $timestamp = $_GET['timestamp'];
 $lat = $_GET['lat'];
 $lon = $_GET['lon'];
 $speed = $_GET['speed'];
 $bearing = $_GET['bearing'];
 $altitude = $_GET['altitude'];
 $info = $_GET['info'];

 if (empty( $_GET['info'])) {
   $info = "";
 } else {
   $info = $_GET['info'];
 }

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
  move_uploaded_file($_FILES['webcam']['tmp_name'], 'uploads/webcam'.time().'.jpg'); 
      // be aware of file / directory permissions on your server

function logsession() {
 global  $timestamp,$id, $lat, $lon,$type,$info;
 //date_default_timezone_set('Asia/Bangkok');
 $datetime = date("F j, Y, g:i a");
 $outString =  $datetime . " : " . $id . " : " . time() . " : ". $lat ." : ". $lon . " : " . $info. " : " . $type . "\n";
 $f = fopen("uploads/mycam_pic.log", "a");
 fwrite( $f, $outString );
 fclose( $f );
 return;
}
 
 logsession();

?>
