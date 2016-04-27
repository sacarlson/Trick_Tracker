<!DOCTYPE html>
<html lang="en">
<head>
  <title> Tricktraker.com Tracker Event Table</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="keywords" content="parties, party, Track, real-time, Girls, Map, GPS, Photos, way points, mark, find friends, people profiles, wiki, database, search">

    <meta name="description" content="A web app real-time updating Map and tracker database to find girls for hire nearby, advertise party locations and track people using your mobile phones Map, GPS and Camera features. ">

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

    var params = {};
    var table_sort_trans = new Tablesort(document.getElementById('table'), {
        descending: true
      });

    var track_time_min = document.getElementById('time');
    var max_speed = document.getElementById('max_speed_record');
    var min_speed = document.getElementById('min_speed_record');
    track_time_min.value = localStorage.getItem("track_time_min");
    min_speed.value = localStorage.getItem("min_speed_record");
    max_speed.value = localStorage.getItem("max_speed_record");

    function clear_table(id) {
      // stupid clear fix to allow table sort to work
      // for reasons uknown can't delete the first data line, can only clear it's contents to keep sort working
      console.log("clear_table");
      var col_count = document.getElementById(id).rows[0].cells.length;        
      var myTable = document.getElementById(id);
      var rowCount = myTable.rows.length;
      for (var x=rowCount-2; x>0; x--) {
        myTable.deleteRow(x);
      }
      var first_row = document.getElementById(id).rows[1].cells;
      for (var x=col_count-1; x>=0; x--) {
        first_row[x].innerHTML = "";
      }   
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
      <a href="../chat.html">Real Time Chat</a>
      <a href="../index.html">Map</a>
      <a href="../send.html">Send Trick Cords</a>
      <a href="../config.html">Config</a>
      <a href="../shoot/index.html">Shoot Pictures</a> 
      <a href="../wiki/doku.php">Wiki</a>
      <a href="./walker.html">Mileage Monitor</a>
    </div>
  </div>
  <h1>Trick Traker Event History Table (sortable)</h1>
  </div>
  <div class="w3-teal w3-container w3-half ">
     <div class="w3-container w3-teal">
       <form action="index.php" method="get" class="w3-container center ">
         <input type="hidden" name="time" id="time" value="none">
         <input type="hidden" name="max_speed" id="max_speed_record" value="5"> 
         <input type="hidden" name="min_speed" id="min_speed_record" value="2">  
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
  <th data-sort-method='number'>Speed</th>
  <th data-sort-method='number'>distance</th>
  <th data-sort-method='number'>total distance</th>
  <th data-sort-method='number'>total cal burn</th>
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
  if (!empty( $_GET['time'])) {
    //echo "get time " . $_GET['time'] . " ";
    $timestamp_now = date_timestamp_get(date_create());
    $from_time = $timestamp_now - ($_GET['time'] * 60);
    $include_sql_and = " AND timestamp > '" . $from_time . "'";
    $include_sql_where = " WHERE timestamp > '" . $from_time . "'";
  } else {
    $include_sql_and = "";
    $include_sql_where = "";
  }
  if (!empty( $_GET['search'])) {
    $sql =  "SELECT * FROM `data` WHERE (id = '" . $_GET['search']. "' OR info = '". $_GET['search'] .
 "' OR type = '". $_GET['search'] ."')". $include_sql_and ." ORDER BY timestamp DESC LIMIT 200";
  } else {
    $sql =  "SELECT * FROM `data`" . $include_sql_where . " ORDER BY timestamp DESC LIMIT 200";
  }

  //echo $sql;

  if(!$result = $conn->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
  }
 $last_lat = 0;
 $last_lon = 0;
 $total_dist = 0;
 if (!empty( $_GET['max_speed'])) {
   $max_speed = $_GET['max_speed'];
 } else {
   $max_speed = 6;
 }
 if (!empty( $_GET['min_speed'])) {
   $min_speed = $_GET['min_speed'];
 } else {
   $min_speed = 1;
 }
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
   $total_cal_burn = $total_dist * 88.9;
   $href = '../map.html?json={%22no_icons%22:%221%22,%22lat%22:%22' . $row['lat'] . '%22,%22lon%22:%22' . $row['lon'] . '%22}';
   $link = '<a href="' . $href .'">Map Link</a>';
   echo '<tr>';
   echo '  <td>' . $row['time'] . '</td>';
   echo '  <td>' . $row['id']. '</td>';
   echo '  <td>' . "username" . '</td>';
   echo '  <td>' . round($row['lat'],7) . '</td>';
   echo '  <td>' . round($row['lon'],7) . '</td>';
   echo '  <td>' . $row['type'] . '</td>';
   echo '  <td>' . round(($speedmph),2) . '</td>';
   echo '  <td>' . round($distmiles,5) . '</td>';
   echo '  <td>' . round($total_dist,5) . '</td>';
   echo '  <td>' . round($total_cal_burn,2) . '</td>';
   echo '  <td>' . $link . '</td>'; 
   //echo '  <td>' . $row['info'] . '</td>';
   echo '  <td>' . $row['timestamp'] . " " . $row['info'] . '</td>';
   echo '</tr>';
   $last_lat = $row['lat'];
   $last_lon = $row['lon'];
   $last_time = $row['timestamp'];
   $last_id = $row['id'];
   if ($speedmph < $max_speed && $speedmph > $min_speed) {
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
