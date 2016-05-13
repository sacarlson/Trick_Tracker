<?php
 // Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->
  //header('Content-type: text/html');
  //header('Access-Control-Allow-Origin:','*');
  //header('Access-Control-Allow-Origin: *'); 
 
 $target_file = "./uploads/" . $_GET['id'] . "_icon.png";
 //echo $target_file . "<br>";
 if (file_exists ( $target_file )) {
    echo "yes";
 } else {
   echo "no";
 }

?>
