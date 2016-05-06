<!doctype html>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="keywords" content=" real-time, Map, GPS, custom, icon ">
        <meta name="description" content="Upload icon image.">

	<title> upload_icon</title>
   <link rel="stylesheet" href="w3.css">
	<style type="text/css">
		
.dropbtn {
    background-color: #4CAF50;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropbtn:hover, .dropbtn:focus {
    background-color: #3e8e41;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    overflow: auto;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown a:hover {background-color: #f1f1f1}

.show {display:block;}


.box { 
  margin: auto;
  width: 100%;
  height: auto;
  margin: 30px;
}

div.img {
    border: 1px solid #ccc;
}

div.img:hover {
    border: 1px solid #777;
}

div.img img {
    width: 100%;
    height: auto;
}

div.desc {
    padding: 15px;
    text-align: center;
}

* {
    box-sizing: border-box;
}

.responsive {
    padding: 0 6px;
    float: left;
    width: 24.99999%;
}

@media only screen and (max-width: 700px){
    .responsive {
        width: 49.99999%;
        margin: 6px 0;
    }
}

@media only screen and (max-width: 500px){
    .responsive {
        width: 100%;
    }
}

.clearfix:after {
    content: "";
    display: table;
    clear: both;
}

 a {
  font-size: calc(1vw + 1vh + 1vmin);
 }

 .center {
    margin: auto;
    width: 60%;
    padding: 20px;
 }

	</style>

  <script>
     "use strict";
window.addEventListener("load", function(event) { 

  document.getElementById("site_hostname").innerHTML= get_site_hostname();
   

  function get_site_hostname() {
    var a = document.createElement('a');
    a.href = window.location.href;
    console.log("site hostname");
    console.log(a['hostname']);
    return a['hostname'];
  } 

});

  function dropDown() {
    document.getElementById("myDropdown").classList.toggle("show");
  }
  

  </script>
</head>
<body>

 <div class="w3-row">
  <div class="w3-teal w3-container w3-rest">
    <div class="dropdown">
      <button onclick="dropDown()" class="dropbtn">Menu</button>
      <div id="myDropdown" class="dropdown-content">
        <a href="../chat.html">Real Time Chat</a>
        <a href="../map.html">Map</a>
        <a href="../send.html">Send Cords</a>
        <a href="../config.html">Config</a>
        <a href="index.html">Shoot Pictures</a> 
        <a href="../wiki/doku.php">Wiki</a> 
      </div>
    </div>
    <h1><span id="site_hostname"></span> Upload Custom User Icon</h1>
  </div>
 </div>
 <div class="w3-row">
  <div class="w3-teal w3-container w3-rest">
<?php
include('config.php');

 // Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// need ImageMagick installed for this to work
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "<h2>File is not an image.</h2>";
        $uploadOk = 0;
    }
}

echo "id =: ";
echo $_GET['id'] . "<br>" ;


// Check file size
if ($_FILES["fileToUpload"]["size"] > 200000) {
    echo "<h2>Sorry, your file is too large.</h2>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "GIF") {
    echo "<h2>Sorry, only jpg, JPG, jpeg, png ,PNG & gif,GIF files are allowed.</h2>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<h2>Sorry, your file was not uploaded.</h2>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "<h2>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</h2>";
      $out=array();
      $err = 0;
      $command = "convert " . $target_file . " -resize 44x44! ./uploads/tmp.png";
      //$run = exec('convert ./uploads/question.png -resize 50x50  ./uploads/question_small.png',$out,$err);
      $run = exec($command,$out,$err);
      
      if (!empty( $_GET['id'])) {
        $save_to = $target_dir . $_GET['id'] . "_icon.png";
      } else {
        $save_to = $target_dir . pathinfo($target_file, PATHINFO_FILENAME) . "_icon.png";
      }
      sleep(2);
      //$command2 = "convert -page 57x68 ./uploads/icon_background.png -page +6+6 ./uploads/tmp.png -layers flatten " .$save_to;
      $command2 = "convert -page 57x68 ./uploads/icon_background.png -page +6+6 ./uploads/tmp.png -alpha Set -virtual-pixel transparent -background none -layers flatten " .$save_to;

      //echo "com2: " . $command2;
      $run = exec($command2,$out,$err);
//convert -page 57x68 icon_background.png -page +6+6 add.png -layers flatten  out.png
      //echo implode ("<br>",$out);
      //print_r($err);
      //print_r($run);
      unlink($target_dir . 'tmp.png');
      unlink($target_file);
      $id = $_GET['id'];
      $sql = "UPDATE users SET type=41 WHERE id=$id"; 
      if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    } else {
        echo "<h2>Sorry, there was an error uploading your file.</h2>";
    }
}
?> 
