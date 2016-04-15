<!doctype html>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Trick Traker Gallery</title>
        <link rel="stylesheet" href="../w3.css">
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
	</style>

 <script>

    function dropDown() {
      document.getElementById("myDropdown").classList.toggle("show");
    }
 </script>
</head>
<body>

<div class="w3-container w3-teal">
  <div class="dropdown">
    <button onclick="dropDown()" class="dropbtn">Menu</button>
    <div id="myDropdown" class="dropdown-content">
      <a href="../index.html">Map</a>
      <a href="../config.html">Config</a>
      <a href="index.html">Shoot Pic</a>  
    </div>
  </div>
  <h1>Trick Traker Gallery</h1>
</div>

<?php
// Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->
  
 include('../../config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


  $sql = "SELECT * FROM `pics`";

  if(!$result = $conn->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
  }
  $count = 1;
  //echo '{"users":[';


while($row = $result->fetch_assoc()){

      //echo '{"id":"' . $row['id']. '","lat":"' . $row['lat']. '","lon":"'. $row['lon']. '","timestamp":"'. $row['timestamp'] . '","pic_file":"'. $row['pic_file'] .'","info":"'. $row['info'] . '","type":"' . $row['type'].'"}';
    
    //if ($count < $result->num_rows) {
   //   echo ',';
  //  }
  $date_form = date("F j, Y, g:i a", $row['timestamp']);

  $map_link = "https://www.tricktraker.com/?json={%22lat%22:%22" . $row['lat'] . "%22,%22lon%22:%22" . $row['lon'] . "%22,%22zoom%22:%2215%22,%22type%22:%22". 0 . "%22,%22info%22:%22". $row['info'] . "%22,%22timestamp%22:%22". $row['timestamp'] . "%22}";

  echo '<div class="responsive">';
  echo '<div class="img">';
  echo '<a target="_blank" href="' . $map_link .'">';
  echo '<img src="uploads/'. $row['pic_file'] .'" width="300" height="200">';
  echo '</a>';
  echo '<div class="desc"> info: Date: '. $date_form ." ". $row['info'] . " lat: " . $row['lat'] . " lon: " . $row['lon']  . '</div>';
  echo ' </div>';
  echo '</div>';
 
}


$result->free();
$conn->close();

// end php
?>
</body>
</html>
