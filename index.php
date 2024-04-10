<?php
include 'makingapi.php';

// CORS access for all URLs

// Replace $apiKey with your actual OpenWeatherMap API key
$apiKey = "a1f754abbd12a175ffd5a267d0775fcd";

// Check if the city name is provided in the URL parameter
$city = isset($_GET['city']) ? $_GET['city'] : '';

if ($city) {
    // URL to fetch current weather data
    $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=" . $apiKey . "&units=metric";

    // Fetching data from the API
    $data = file_get_contents($url);
    $content = json_decode($data);
    

    // Making connection to MySQL
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "prototype2";

    $connection  = mysqli_connect($servername, $username, $password, $dbname);
    

    // Check if the database connection is successful
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        echo 'Connected';
    }

    // Accessing required values from the weather data
    $city_name = isset($content->name) ? $content->name : '';
    $temperature = isset($content->main->temp) ? $content->main->temp : '';
    $pressure = isset($content->main->pressure) ? $content->main->pressure : '';
    $humidity = isset($content->main->humidity) ? $content->main->humidity : '';
    $wind_speed = isset($content->wind->speed) ? $content->wind->speed : '';
    $wind_deg = isset($content->wind->deg) ? $content->wind->deg : '';
    $visibility = isset($content->visibility) ? $content->visibility : '';
    $dt = isset($content->dt) ? $content->dt : '';
    $weather_description = isset($content->weather[0]->description) ? $content->weather[0]->description : '';
    $icon = isset($content->weather[0]->icon) ? $content->weather[0]->icon : '';

    // Insert values into the "weather_data" table for current weather entry
    if ($city_name && $temperature && $humidity && $pressure && $wind_speed && $wind_deg && $visibility && $dt && $weather_description && $icon) {
        $query = "INSERT INTO weather_data (CityName, Temperature, Humidity, Pressure, WindSpeed, WindDeg, Visibility, Weather_Description, Icon, DateTime) 
                  VALUES ('$city_name', $temperature, $humidity, $pressure, $wind_speed, $wind_deg, $visibility, '$weather_description', '$icon', FROM_UNIXTIME($dt))";

        // Perform the query and check for errors
        if (mysqli_query($connection, $query)) {
            echo "Data inserted successfully!";
        } else {
            die("Insertion failed: " . mysqli_error($connection));
        }
    }
    
    // Close the database connection
    mysqli_close($connection);

}
?>