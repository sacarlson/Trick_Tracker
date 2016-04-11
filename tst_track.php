<?php


function logsession() {
 $id = $_GET['id'];
 $timestamp = $_GET['timestamp'];
 $lat = $_GET['lat'];
 $lon = $_GET['lon'];
 $speed = $_GET['speed'];
 $bearing = $_GET['speed'];
 $altitude = $_GET['altitude'];
 $batt = $_GET['batt'];

 date_default_timezone_set('Asia/Bangkok');
 $datetime = date("F j, Y, g:i a");
 $outString =  $datetime . " : " . $id . " : " . $timestamp . " : ". $lat ." : ". $lon . " : " . $speed . " : " . $bearing . " : " . $altitude  . " : " . $batt . " \n";
 $f = fopen("./session_track.log", "a");
 fwrite( $f, $outString );
 fclose( $f );
 return;
 }

 logsession();
 //echo "ok";
?>
