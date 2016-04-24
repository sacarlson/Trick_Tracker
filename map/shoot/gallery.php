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
  var type = 0;
  var tracker_server ;
  var tracker_get_server ;
  var info ;
  var lat;
  var lon;
  var radius_filter;
    
  var id = 0;
  
  var http = new XMLHttpRequest();


  function dropDown() {
    document.getElementById("myDropdown").classList.toggle("show");
  }

  

  function get_config() {
    console.log("get_config called");
    if (typeof(Storage) !== "undefined") {
       id = localStorage.getItem("id");
       lat = localStorage.getItem("lat");
       lon = localStorage.getItem("lon");
       //zoom = localStorage.getItem("zoom");
       //track_id = localStorage.getItem("track_id");
       //track_time_min = localStorage.getItem("track_time_min");
       type = localStorage.getItem("icon_type");
       info = localStorage.getItem("info");
       tracker_server = localStorage.getItem("tracker_server");
       tracker_get_server = localStorage.getItem("tracker_get_server");
       radius_filter = localStorage.getItem("radius_filter");
       console.log("id");
       console.log(id);
       console.log(typeof id);
       console.log(id.length);
        if ( id == null || id.length == 0) {
         console.log("id was undefined will create one");
         id = randomIntFromInterval(300000,999000);
         localStorage.setItem("id", id);
         localStorage.setItem("lat", 12.93419);
         localStorage.setItem("lon", 100.892515 );
         localStorage.setItem("zoom", 15);
         localStorage.setItem("track_id", 206648);
         localStorage.setItem("track_time_min", 1500); //1500 = 25 hours
         localStorage.setItem("icon_type", 0);
         localStorage.setItem("info", "");
         localStorage.setItem("tracker_server", "https://www.tricktraker.com/record_track.php");
         localStorage.setItem("tracker_get_server", "https://www.tricktraker.com/get_track.php");
         localStorage.setItem("update_interval", "4");
         localStorage.setItem("irc_server", "wilhelm.freenode.net");
         localStorage.setItem("cam_resolution", "low");
         localStorage.setItem("filter", "");
         // radius_filter is in Meters
         localStorage.setItem("radius_filter", "4000");
         get_config();
       } 
    } else {
       id = randomIntFromInterval(300000,999000);
       alert("Sorry, your browser does not support Web Storage.. your ID now: " + id);       
    }
  }

  function randomIntFromInterval(min,max) {
    return Math.floor(Math.random()*(max-min+1)+min);
  }

  function getLocation() {
    console.log("getLocation called");
    if (navigator.geolocation) {
        //navigator.geolocation.watchPosition(showPosition);
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
       alert("Geolocation is not supported by this browser.");
    }
  }
    
function showPosition(position) {
    console.log( position.coords.latitude ); 
    console.log( position.coords.longitude );
    console.log( type);
    var timestamp = Math.floor(Date.now() / 1000);
//GET /?id=206648&timestamp=1459757028&lat=12.9304184&lon=100.8798106&speed=0.0&bearing=0.0&altitude=0.0&batt=100.0
    var url = tracker_server + "?id=" + id + "&timestamp=" + timestamp + "&lat=" + position.coords.latitude + "&lon=" + position.coords.longitude + "&type=" + type;
    
    console.log("url: " + url);	
    http.open("GET", url, true);
    http.send();
    console.log("get url");
    console.log(url);
    lat = position.coords.latitude;
    lon = position.coords.longitude;
    alert(" your position seen as lat: " + position.coords.latitude + " lon: " + position.coords.longitude + " type: " + type + " id: " + id );
    
}
 
  get_config();
  getLocation();

 </script>
</head>
<body>

 <div class="w3-row">
  <div class="w3-teal w3-container w3-half">
    <div class="dropdown">
    <button onclick="dropDown()" class="dropbtn">Menu</button>
    <div id="myDropdown" class="dropdown-content">
      <a href="../chat.html">Real Time Chat</a>
      <a href="../index.html">Map</a>
      <a href="../send.html">Send Trick Cords</a>
      <a href="../config.html">Config</a>
      <a href="../shoot/index.html">Shoot Pictures</a> 
      <a href="http://wiki.tricktraker.com">Wiki.tricktraker.com</a> 
    </div>
  </div>
  <h1>Trick Traker Gallery</h1>
  </div>
  <div class="w3-teal w3-container w3-half ">
     <div class="w3-container w3-teal">
       <form action="gallery.php" method="get" class="w3-container center ">
         <label>Search</label>
         <input type="text" name="search" class="w3-input w3-text-black">
         <input type="submit" class="w3-btn w3-blue" >
       </form>
     </div>    
  </div>
</div>

<?php
// Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->
  
 include('../../config.php');


  function distanceGeoPoints ($lat1, $lng1, $lat2, $lng2) {

    $earthRadius = 3958.75;

    $dLat = deg2rad($lat2-$lat1);
    $dLng = deg2rad($lng2-$lng1);


    $a = sin($dLat/2) * sin($dLat/2) +
       cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
       sin($dLng/2) * sin($dLng/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $dist = $earthRadius * $c;

    // from miles
    $meterConversion = 1609;
    $geopointDistance = $dist * $meterConversion;

    return $geopointDistance;
  }

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


  //$sql = "SELECT * FROM `pics`";
  //$sql =  "SELECT * FROM `pics` ORDER BY timestamp DESC LIMIT 32";
  if (!empty( $_GET['search'])) {
    $sql =  "SELECT * FROM `pics` WHERE id = '" . $_GET['search']. "' OR info = '". $_GET['search'] .
 "' OR type = '". $_GET['search'] . "' OR pic_file = '" . $_GET['search'] . "' ORDER BY timestamp DESC LIMIT 32";
    //echo $sql;
  } else {
    $sql =  "SELECT * FROM `pics` ORDER BY timestamp DESC LIMIT 32";
    //echo $sql;
  }

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
      if (!empty( $_GET['lat']) && !empty($_GET['lon'])){
        $distance = distanceGeoPoints ($_GET['lat'], $_GET['lon'], $row['lat'], $row['lon']);
      } else {
        $distance = 0;
      }

  if (!empty( $_GET['radius']) && ($_GET['radius'] < $distance) && (strlen($_GET['radius']) > 0)){
     // skip distance too far a way
     //echo "skip";
  } else {
    $date_form = date("F j, Y, g:i a", $row['timestamp']);

    $map_link = "https://www.tricktraker.com/?json={%22lat%22:%22" . $row['lat'] . "%22,%22lon%22:%22" . $row['lon'] . "%22,%22zoom%22:%2215%22,%22icon_type%22:%22". 16 . "%22,%22info%22:%22". $row['info'] . "%22,%22timestamp%22:%22". $row['timestamp'] . "%22,%22pic_file%22:%22" . $row['pic_file'] . "%22,%22id%22:%22" . $row['id']. "%22}";
    $wiki_link = "http://wiki.tricktraker.com/doku.php?id=tricktraker.com:gallery:" . $row['pic_file'];
    echo '<div class="responsive">';
    echo '<div class="img">';
    //echo '<a target="_blank" href="' . $map_link .'">';
    echo '<a target="_blank" href="' . $wiki_link .'">';
    echo '<img src="uploads/'. $row['pic_file'] .'" width="300" height="200">';
    echo '</a>';
    echo '<div class="desc"> Shared by: ' . $row['id'] . ' On:  '. $date_form ." " . $row['info'] . " lat: " . $row['lat'] . " lon: " . $row['lon'] ." type: ". $row['type'] . " pic_file: " . $row['pic_file'] . " distance: " . intval($distance) . " Meters from Center Map " . '</div>';
    echo ' </div>';
    echo '</div>';
  }
 
}


$result->free();
$conn->close();

// end php
?>
</body>
</html>
