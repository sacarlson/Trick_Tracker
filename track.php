<?php
 // Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->
  //header('Content-type: text/html');
  //header('Access-Control-Allow-Origin:','*');
  header('Access-Control-Allow-Origin: *'); 

 include('config.php');

 $id = $_GET['id'];
 $count = $_GET['count'];
 $start = $_GET['start'];
 $stop = $_GET['stop'];
 $extra = $_GET['extra'];
 $type = $_GET['type'];
 $last = $_GET['last'];
 
 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!empty( $_GET['last'])) {
  $sql = "SELECT * FROM `users`";

  if(!$result = $conn->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
  }
  $count = 1;
  echo '{"users":[';


while($row = $result->fetch_assoc()){

      echo '{"username":"' . $row['username']. '","lat":"' . $row['last_lat']. '","lon":"'. $row['last_lon']. '","timestamp":"'. $row['timestamp'] . '","id":"'. $row['traccar_id'] .'","info":"'. $row['info'] . '","type":"' . $row['type'].'"}';
    
    if ($count < $result->num_rows) {
      echo ',';
    }
    $count = $count + 1;
}

echo '],"count":"'. $result->num_rows .'"}';
$result->free();
$conn->close();

} else {
  // ***************************************************************

  $sql = "SELECT * FROM `data`";

if (!empty($_GET['id']) && ($_GET['id'] > 10)) {
  $sql = $sql . " WHERE id = ". $id;
} else {
  if (!empty($_GET['type'])) {
    if ($type == 99) {
      $sql = $sql . " WHERE type != '0'";
    } else {
      $sql = $sql . " WHERE type = '$type'";
    }
  } else {
    $sql = $sql . " WHERE type = 0";
  }
}


if (!empty( $_GET['start'])) {
  $sql = $sql . " AND timestamp > ". $start;
}

if (!empty( $_GET['stop']) and !empty($_GET['start'])) {
  $sql = $sql . " AND timestamp < ". $stop;
}


// I don't think you can have both start and stop and still use count but not sure yet, hope it's not needed and won't be checking here.
if (!empty( $_GET['count'])) {
   $sql = $sql .  " ORDER BY timestamp DESC" ." LIMIT ". $count ;
 } else {
   $sql = $sql .  " ORDER BY timestamp DESC";
 }

//echo "sql: " . $sql . "<br>";

if(!$result = $conn->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

$count = 1;
echo '{"id":"'.$id. '","track":[';


while($row = $result->fetch_assoc()){
    if (!empty( $_GET['extra'])) {
      echo '{"lat":"' . $row['lat']. '","lon":"'. $row['lon']. '","timestamp":"'. $row['timestamp'] . '","speed":"'. $row['speed']. '","bearing":"'. $row['bearing']. '","alt":"'. $row['altitude']. '","id":"'. $row['id'] .'","comment":"'. $row['comment'] . '","type":"' . $row['type'].'"}';
    } else {
      echo '{"lat":"' . $row['lat']. '","lon":"'. $row['lon']. '","timestamp":"'. $row['timestamp'] . '","type":"'. $row['type']. '"}';
    }
    if ($count < $result->num_rows) {
      echo ',';
    }
    $count = $count + 1;
}

echo '],"count":"'. $result->num_rows .'"}';
 


$result->free();

//if ($result = $conn->query($sql) === TRUE) {
    //echo "New record created successfully";
//   $status = " record success";
//} else {
//    //echo "Error: " . $sql . "<br>" . $conn->error;
//    $status = "Error: " . $conn->error;
//}

$conn->close();

} // end if last

?>
