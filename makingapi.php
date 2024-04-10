<?php
ini_set('display_errors', 1);
// cors access for all urls


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prototype2";

// connecting to the database
$connection = mysqli_connect($servername, $username, $password, $dbname);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo 'Connected';
}

// using weatherapp database
mysqli_query($connection, 'USE weatherapp');

// selecting data from weather_data table
$query = 'SELECT * FROM weather_data';
$result = mysqli_query($connection, $query);

// check if the query executed successfully
if ($result === false) {
    die("Query failed: " . mysqli_error($connection));
}

// check if any rows were returned
if (mysqli_num_rows($result) === 0) {
    die("No data found.");
}

// converting the data to an array of objects
$weatherdata = array();
while ($row = mysqli_fetch_assoc($result)) {
    $weatherdata[] = $row;
}

// returning values in JSON format
echo json_encode($weatherdata);
?>
