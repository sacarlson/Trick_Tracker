<?php
 // Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->
  //header('Content-type: text/html');
  //header('Access-Control-Allow-Origin:','*');
  header('Access-Control-Allow-Origin: *'); 

// examples
// https://api.tricktraker.com/track2.php/?mode=all

 include('config.php');

 //$_GET['id'];     // user ID to search, for special case of less than 10 it won't search for id
 //$_GET['limit'];  // limit record number to return
 //$_GET['start'];  // timestamp to start or min
 //$_GET['stop'];   // timestamp to stop or max
 // $_GET['extra'];  // 1 or true adds more data returned from traccar from data with added altitude, speed, bearing
 // $_GET['type'];    // type to search, type indicates icon type, for special case of type = 99, will return all but type != 0 
 // $_GET['mode']  mode  of "user", "data" , "pics", "all" to mark table to search, in "all" mode data table returns only type !=0 and all points in user, pics tables.  this is due to type=0 being the traccar data type that can get very big with many points, only used for tracks.
 // $_GET['lat'] reference of your present lat position to measure distance from targets
 // $_GET['lon'] reference your long postion, if provided a distance will be provided in return data. if radius is also provided then it will be used in filter
 // $_GET['radius'] max distance radius to return targets (in Meters?), if not provided (empty) no filter of data is done just distance provided. 
 // $_GET['sort']  sort by "distance"  or "time"  (not implemented)

 // Create connection
 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
 }

  switch($_GET['mode']) {
    case 'all':
        echo '{"all":[';
        $_GET['id'] = 1;
        $_GET['mode'] = "user";
        $_GET['type'] = 99;
        get_list();
        $_GET['mode'] = "pics";
        echo ',';
        get_list();        
        $_GET['mode'] = "data";
        echo ',';
        get_list();
        echo ']}';        
        break;
    default: // default to just data table
        get_list();
  } 
 
 function logsession($data) {
   //date_default_timezone_set('Asia/Bangkok');
   $datetime = date("F j, Y, g:i a");
   $outString =  $datetime . " : " . $data .  "\n";
   $f = fopen("./session_track_get.log", "a");
   fwrite( $f, $outString );
   fclose( $f );
   return;
 }

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

  function get_list() {
    global $_GET, $conn;
    $where_flag = false;
    switch($_GET['mode']) {
      case 'pics':
        $sql = "SELECT * FROM `pics`"; 
        break;
      case 'user':
         $sql = "SELECT * FROM `users`";
        break;
      default: // default to data
        $sql = "SELECT * FROM `data`";
    } 

    if (!empty($_GET['id']) && ($_GET['id'] > 10)) {
      $sql = $sql . " WHERE id = ". $_GET['id'];
      $where_flag = true;
    }

    if (!empty($_GET['type'])) {
      if ($where_flag) {
        $sql = $sql . " AND";
      } else {
        $sql = $sql . " WHERE";
      }
      if ($_GET['type'] == 99) {
        $sql = $sql . " type != '0'";
      } else {
        $sql = $sql . " type = '" . $_GET['type'] . "'";
      }
      $where_flag = true;
    }
      
    

    if (!empty( $_GET['start'])) {
      if ($where_flag) {
        $sql = $sql . " AND";
      } else {
        $sql = $sql . " WHERE";
      }
      $where_flag = true;
      $sql = $sql . " timestamp > ". $_GET['start'];
    }

    // note: at this time you can't add a stop without already having a start,  do we need this?
    if (!empty( $_GET['stop']) && !empty($_GET['start'])) {
      if ($where_flag) {
        $sql = $sql . " AND";
      } else {
        $sql = $sql . " WHERE";
      }
      $where_flag = true;
      $sql = $sql . " timestamp < ". $_GET['stop'];
    }

    if (!empty( $_GET['limit'])) {
     $sql = $sql .  " ORDER BY timestamp DESC" ." LIMIT ". $_GET['limit'] ;
    } else {
     $sql = $sql .  " ORDER BY timestamp DESC";
    }

    //echo "sql: " . $sql . "<br>";

    if(!$result = $conn->query($sql)){
      die('There was an error running the query [' . $db->error . ']');
    }

    $count = 1;

    switch($_GET['mode']) {
      case 'pics':
        echo '{"pics":['; 
        break;
      case 'user':
        echo '{"users":[';
        break;
      default: // default to data
        echo '{"data":[';
    } 

    while($row = $result->fetch_assoc()){
     // if (!empty($_GET['lat']) && (!empty($_GET['lon'])) && (!empty($_GET['distance_max'])) &&
 //(distanceGeoPoints ($_GET['lat'], $_GET['lon']), $row['lat'], $row['lon']) < $_GET['distance_max']))) {
         // skip as we are outside distance range of interest
     // } else 
      if (!empty( $_GET['lat']) && !empty($_GET['lon'])){
        $distance = distanceGeoPoints ($_GET['lat'], $_GET['lon'], $row['lat'], $row['lon']);
      } else {
        $distance = 0;
      }
      if (!empty( $_GET['radius']) && ($_GET['radius'] < $distance) && (strlen($_GET['radius']) > 0)){
         // skip distance too far a way
         //echo "skip";
      } else {
        if (!empty( $_GET['extra'])) {
          $out = '{"lat":"' . $row['lat']. '","lon":"'. $row['lon']. '","timestamp":"'. $row['timestamp'] . '","speed":"'. $row['speed']. '","bearing":"'. $row['bearing']. '","alt":"'. $row['altitude']. '","id":"'. $row['id'] .'","info":"'. $row['info'] . '","type":"' . $row['type'] . '","distance":"' . $distance;
        } else {
          $out = '{"id":"'. $row['id'] . '","lat":"' . $row['lat']. '","lon":"'. $row['lon'].'","info":"'. $row['info'] . '","timestamp":"'. $row['timestamp'] . '","type":"'. $row['type'] . '","distance":"' . $distance;
        }

        switch($_GET['mode']) {
        case 'pics':
          $out = $out . '","pic_file":"' . $row['pic_file'] . '"}'; 
          break;
        case 'user':
           $out = $out . '","username":"' . $row['username'] . '"}'; 
          break;
        default: // default to data
           $out = $out . '"}';
        } 
        echo $out;
        if ($count < $result->num_rows) {
          echo ',';
        }
      } // end not skiped
      $count = $count + 1;
    }

    echo '],"count":"'. $result->num_rows .'"}';

    $result->free();

    //$conn->close();

  } // end get_list()

  //if (!empty( $_GET['last'])) {

  
 $conn->close();
 //logsession();

?>
