<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prototype2";

$connection = mysqli_connect($servername, $username, $password, $dbname);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$queryLastCity = "SELECT CityName FROM weather_data ORDER BY id DESC LIMIT 1";
$resultLastCity = mysqli_query($connection, $queryLastCity);

if (!$resultLastCity) {
    die("Query failed: " . mysqli_error($connection));
}

$rowLastCity = mysqli_fetch_assoc($resultLastCity);
$defaultCity = $rowLastCity['CityName'];
$currentDate = date('Y-m-d');
$daysToSubtract = 7; // Change this value to adjust the number of past days to fetch

$endDate = $currentDate;
$startDate = date('Y-m-d', strtotime("-$daysToSubtract days", strtotime($currentDate)));

$dateRange = [];
$currentDate = strtotime($startDate);
while ($currentDate <= strtotime($endDate)) {
    $dateRange[] = date('Y-m-d', $currentDate);
    $currentDate = strtotime('+1 day', $currentDate);
}

$data = [];

foreach ($dateRange as $uniqueDate) {
    $dayName = date('l', strtotime($uniqueDate));

    $data[] = [
        "Day" => $dayName,
        "Date" => $uniqueDate,
        "City" => $defaultCity
    ];

    $queryDetailedWeather = "SELECT Temperature, Humidity, Pressure, WindSpeed, WindDeg, Visibility, Weather_Description, Icon, DateTime
                             FROM weather_data
                             WHERE CityName = '$defaultCity' AND DATE(DateTime) = '$uniqueDate'
                             LIMIT 1";

    $resultDetailedWeather = mysqli_query($connection, $queryDetailedWeather);

    if (!$resultDetailedWeather) {
        die("Query failed: " . mysqli_error($connection));
    }

    $rowDetailedWeather = mysqli_fetch_assoc($resultDetailedWeather);

    if ($rowDetailedWeather) {
        $data[count($data) - 1]["Temperature"] = $rowDetailedWeather['Temperature'];
        $data[count($data) - 1]["Humidity"] = $rowDetailedWeather['Humidity'];
        $data[count($data) - 1]["Pressure"] = $rowDetailedWeather['Pressure'];
        $data[count($data) - 1]["WindSpeed"] = $rowDetailedWeather['WindSpeed'];
        $data[count($data) - 1]["WindDeg"] = $rowDetailedWeather['WindDeg'];
        $data[count($data) - 1]["Visibility"] = $rowDetailedWeather['Visibility'];
        $data[count($data) - 1]["Weather_Description"] = $rowDetailedWeather['Weather_Description'];
        $data[count($data) - 1]["Icon"] = $rowDetailedWeather['Icon'];
    }
}

mysqli_close($connection);

echo json_encode($data);
?>
