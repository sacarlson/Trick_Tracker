<!DOCTYPE html>
<html lang="en">
<head>
  <title> FunTracker.Site Tracker Event Table</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="keywords" content="search, sort ,database, GPS, pictures, gallery, table format">
  <meta name="description" content="See a list of every picture taken from the FunTracker.site Camera in a searchable, sortable table format. ">

  <link rel="stylesheet" href="../w3.css">
  <link rel="stylesheet" href="tab.css">  
  <link rel="stylesheet" href="table.css"> 

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
    z-index: 10;
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

  <script src="js/tape.js"></script>
  <script src='js/tablesort.js'></script>  
  <script src='js/tablesort.number.js'></script>
 
  <script>
    "use strict";
   
    // Initialize everything when the window finishes loading
    window.addEventListener("load", function(event) {

    var table_sort_trans = new Tablesort(document.getElementById('table'), {
        descending: true
      });

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

<div class="w3-row" >
  <div class="w3-teal w3-container w3-half">
    <div class="dropdown">
    <button onclick="dropDown()" class="dropbtn">Menu</button>
    <div id="myDropdown" class="dropdown-content" >      
      <a href="https://chat.funtracker.site/channel/pattaya_gossip_room">Real Time Chat</a>
      <a href="../map.html">Map</a>
      <a href="../send.html">Send Cords</a>
      <a href="../config.html">Config</a>
      <a href="../shoot/index.html">Shoot Pictures</a> 
      <a href="../wiki/doku.php">Wiki</a> 
    </div>
  </div>
  <h1><span id="site_hostname"></span> Picture Event History Table (sortable)</h1>
  </div>
  <div class="w3-teal w3-container w3-half ">
     <div class="w3-container w3-teal">
       <form action="pic_table.php" method="get" class="w3-container center ">
         <label>Search</label>
         <input type="text" name="search" class="w3-input w3-text-black">
         <input type="submit" class="w3-btn w3-blue" >
       </form>
     </div>    
  </div>
</div>

<div id="trans" class="tab-pane fade" >
  
  <div class="table-responsive" >
<table id="table" >
<thead>
<tr>
  <th class='sort-default'>Event Date/Time</th>
  <th data-sort-method='number'>TT ID#</th>
  <th>User Name</th>
  <th data-sort-method='number'>Lat</th>
  <th data-sort-method='number'>Long</th>
  <th>Type</th>
  <th>Pic Preview</th>
  <th>Map Link</th>
  <th>Info</th>
</tr>
</thead>
<tbody>

<?php
// Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->

  include('../config.php');


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
  //$sql =  "SELECT * FROM `data` ORDER BY timestamp DESC LIMIT 32";
  if (!empty( $_GET['search'])) {
    $sql =  "SELECT * FROM `pics` WHERE id = '" . $_GET['search']. "' OR info = '". $_GET['search'] .
 "' OR type = '". $_GET['search'] . "' ORDER BY timestamp DESC LIMIT 40";
  } else {
    $sql =  "SELECT * FROM `pics` ORDER BY timestamp DESC LIMIT 40";
  }

  //echo $sql;

  if(!$result = $conn->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
  }
 $last_lat = 0;
 $last_lon = 0;
 $total_dist = 0;
 while($row = $result->fetch_assoc()){
   if ($last_lat > 0  && $last_id == $row['id']) {
     $dist = distanceGeoPoints ($last_lat, $last_lon, $row['lat'], $row['lon']);
     // speed is in meter per second
     $speed = $dist/($last_time - $row['timestamp']);
     $speedmph = $speed * 2.236936292;
     $distmiles = $dist * 0.000621371;
   } else {
     $dist = 0;
     $speed = 0;
   }
   $total_cal_burn = ($distmiles + $total_dist) * 88.9;
   $href = '../shoot/gallery.php?search=' . $row['pic_file'];
   $gallery_link = '<a href="' . $href .'">Galler Link</a>';
   $href = '../map.html?json={%22no_icons%22:%221%22,%22lat%22:%22' . $row['lat'] . '%22,%22lon%22:%22' . $row['lon'] . '%22}';
   $map_link = '<a href="' . $href .'">Map Link</a>';
   $timedate = date('m/d/Y H:i:s', $row['timestamp']);
   $pic_preview_html = '<a href="../shoot/uploads/'. $row['pic_file'] . '"><img src="../shoot/uploads/' . $row['pic_file'] . '" style="width:80px;height:50px;" >';
   echo '<tr>';
   echo '  <td>' . $timedate . '</td>';
   echo '  <td>' . $row['id']. '</td>';
   echo '  <td>' . "username" . '</td>';
   echo '  <td>' . round($row['lat'],7) . '</td>';
   echo '  <td>' . round($row['lon'],7) . '</td>';
   echo '  <td>' . $row['type'] . '</td>';
   //echo '  <td>' . $row['pic_file'] . '</td>';
   echo '  <td>' . $pic_preview_html . '</td>';
   echo '  <td>' . $map_link . '</td>';
   echo '  <td>' . $row['info'] . '</td>';
   echo '</tr>';
   $last_lat = $row['lat'];
   $last_lon = $row['lon'];
   $last_time = $row['timestamp'];
   $last_id = $row['id'];
   if ($speed < 6) {
     $total_dist = $total_dist + $distmiles;
   }
 }
 $result->free();
 $conn->close();

// end php
?>

</tbody>
</table>
</div>
</div>

</body>
</html>
