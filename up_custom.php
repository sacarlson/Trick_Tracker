<!doctype html>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="keywords" content=" real-time, Map, GPS, Icon, way points, mark">
        <meta name="description" content="Upload custom user icon for Map.">

	<title> Custom Icon</title>
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

  var params = {};
  get_config();  

  document.getElementById("site_hostname").innerHTML= get_site_hostname();
  var formstr = '<form action="upload_icon.php?id=' + params.id + '" method="post" enctype="multipart/form-data"> Select image to upload: <input type="file" name="fileToUpload" id="fileToUpload">  <input type="submit" value="Upload Image" name="submit"></form>';
  console.log("formstr");
  console.log(formstr);
  //document.getElementById("form").innerHTML= 'test5';
  document.getElementById("form").innerHTML= formstr ;
  
  function randomIntFromInterval(min,max) {
    return Math.floor(Math.random()*(max-min+1)+min);
  }

  function get_config() {
    if (typeof(Storage) !== "undefined") {
       params.id = localStorage.getItem("id");
       params.lat = localStorage.getItem("lat");
       params.lon = localStorage.getItem("lon");
       params.zoom = localStorage.getItem("zoom");
       params.track_id = localStorage.getItem("track_id");
       params.track_time_min = localStorage.getItem("track_time_min");
       params.icon_type = localStorage.getItem("icon_type");
       params.info = localStorage.getItem("info");
       params.tracker_server = localStorage.getItem("tracker_server");
       params.tracker_get_server = localStorage.getItem("tracker_get_server");
       params.update_interval = localStorage.getItem("update_interval");
       params.filter = localStorage.getItem("filter");
       params.radius_filter = localStorage.getItem("radius_filter");
       params.send_cord_interval = localStorage.getItem("send_cord_interval");
       params.enable_send_cord = localStorage.getItem("enable_send_cord");
       params.min_speed_record = localStorage.getItem("min_speed_record");
       params.max_speed_record = localStorage.getItem("max_speed_record");
       console.log("params");
       console.log(params);
       console.log("typeof params.id");
       console.log(typeof params.id);
       console.log(params.id);
       //console.log(params.id);
       if ( params.id == null || params.id.length == 0) {
         console.log("id was undefined will create one");
         params.id = randomIntFromInterval(300000,999000);
         localStorage.setItem("id", params.id);
         localStorage.setItem("lat", 12.93419);
         localStorage.setItem("lon", 100.892515 );
         localStorage.setItem("zoom", 15);
         localStorage.setItem("track_id", 206648);
         localStorage.setItem("track_time_min", 120);
         localStorage.setItem("icon_type", 12);
         localStorage.setItem("info", "");
         localStorage.setItem("tracker_server", "https://www.funtracker.site/record_track.php");
         localStorage.setItem("tracker_get_server", "https://www.funtracker.site/get_track.php");
         localStorage.setItem("update_interval", "3");
         localStorage.setItem("irc_server", "wilhelm.freenode.net");
         localStorage.setItem("cam_resolution", "low");
         localStorage.setItem("filter", "");
         localStorage.setItem("send_cord_interval", "60");
         localStorage.setItem("enable_send_cord", "1");
         localStorage.setItem("min_speed_record", "1");
         localStorage.setItem("max_speed_record", "6");
         // radius_filter is in Meters
         localStorage.setItem("radius_filter", "4000");
         get_config();
       } 
    } else {
       params.id = randomIntFromInterval(300000,999000);
       alert("Sorry, your browser does not support Web Storage, time to upgrade.. your ID now: " + id);       
    }
  }

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
    <span id="form"></span>
  </div>
</div>
</body>
</html> 
