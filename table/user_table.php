<!DOCTYPE html>
<html lang="en">
<head>
  <title>Users List</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="keywords" content="users, search, sort ,database, GPS, track, way points, table format">
  <meta name="description" content="See every unique GPS tracked user and there last known cordinates in the FunTracker.site database in searchable, sortable table format. ">

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

    document.getElementById("site_hostname").innerHTML= get_site_hostname();

    function get_site_hostname() {
      var a = document.createElement('a');
      a.href = window.location.href;
      console.log("site hostname");
      console.log(a['hostname']);
      return a['hostname'];
    } 


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
      <a href="../map.html">Map</a>
      <a href="../send.html">Send Cords</a>
      <a href="../config.html">Config</a>
      <a href="../shoot/index.html">Shoot Pictures</a> 
      <a href="../wiki/doku.php">Wiki</a>
      <a href="./walker.html">Mileage Monitor</a>
    </div>
  </div>
  <h1><span id="site_hostname"></span> Users List (sortable)</h1>
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
  <th data-sort-method='number'> ID#</th>
  <th>User Name</th>
  
  <th data-sort-method='number'>Lat</th>
  <th data-sort-method='number'>Long</th>
  <th data-sort-method='number'>Type</th>
  <th>Map Link</th>
  <th>Icon Image</th>
  <th data-sort-method='number'>TimeStamp</th>
  <th>Info</th>
</tr>
</thead>
<tbody>

<?php
// Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->

  include('../config.php');
  
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
    $sql =  "SELECT * FROM `users` WHERE (id = '" . $_GET['search']. "' OR info = '". $_GET['search'] .
 "' OR type = '". $_GET['search'] ."')". $include_sql_and ." ORDER BY timestamp DESC LIMIT 200";
  } else {
    $sql =  "SELECT * FROM `users`" . $include_sql_where . " ORDER BY timestamp DESC LIMIT 200";
  }

  //echo $sql;

  if(!$result = $conn->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
  }
 
 while($row = $result->fetch_assoc()){
   
   $href = '../map.html?json={%22no_icons%22:%221%22,%22lat%22:%22' . $row['lat'] . '%22,%22lon%22:%22' . $row['lon'] . '%22,%22icon_type%22:%22' . $row['type'] . '%22,%22username%22:%22' . $row['username'] . '%22,%22timestamp%22:%22'. $row['timestamp'] . '%22,%22id%22:%22' . $row['id'] .'%22}';
   $link = '<a href="' . $href .'">Map Link</a>';
   $timedate = date('m/d/Y H:i:s', $row['timestamp']);
   if ($row['type'] == 41) {
     $image_html = '<img src="../uploads/' . $row['id'] . '_icon.png" >';
   } else {
     // $image_html = '<img src="../images/icon_0.png" >';
     $image_html = '<img src="../images_red/icon_' . $row['type'] . '.png" >';
   }

   echo '<tr>';
   echo '  <td>' . $timedate . '</td>';
   echo '  <td>' . $row['id']. '</td>';
   echo '  <td>' . $row['username'] . '</td>';
   echo '  <td>' . round($row['lat'],7) . '</td>';
   echo '  <td>' . round($row['lon'],7) . '</td>';
   echo '  <td>' . $row['type'] . '</td>';   
   echo '  <td>' . $link . '</td>'; 
   echo '  <td>' . $image_html . '</td>'; 
   echo '  <td>' . $row['timestamp'] . '</td>';
   echo '  <td>' . $row['info'] . '</td>';
   echo '</tr>';      
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
