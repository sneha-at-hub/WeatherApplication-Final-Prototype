let inputValue = document.getElementById("searchbar");
let city = document.querySelector(".name");
let temperature = document.querySelector(".temp");
let windSpeed = document.querySelector(".wind");
let visibilityElement = document.querySelector(".visibility");
let humiditydisplay = document.querySelector(".humm");
let weathercondition = document.getElementById("rain");
let button = document.getElementById("submitb");
let icon = document.getElementById("icon");
let date = document.getElementById("date");
let direction =  document.querySelector(".direction");
let pressureElement = document.getElementById("pressure-value");

console.log("City:", city); // Check if the correct city is being logged


function toggleSearchBar() {
  var searchBox = document.getElementById("searchbar");
  if (window.innerWidth <= 640){
    if (searchBox.classList.contains("hidden")) {
      // If the search box is hidden, show it
      searchBox.classList.remove("hidden");
      searchBox.focus(); // Set focus on the input field for better user experience
    } else {
      // If the search box is shown, hide it
      searchBox.classList.add("hidden");
    }
  }
}



function gettingWeather(val) {
  fetch(
    "https://api.openweathermap.org/data/2.5/weather?q=" +
    val +
    "&appid=a1f754abbd12a175ffd5a267d0775fcd"
  )
  .then((response) => response.json())
  .then((data) => {
    const { name, sys } = data;
    const { temp, humidity, pressure} = data.main;
    const { speed, deg } = data.wind;
    const description = data.weather[0].description;
    const visibility = data.visibility;

    // Capitalize the first letter of the description
    const capitalizedDescription = description.charAt(0).toUpperCase() + description.slice(1);

    // Assuming data.weather[0].icon gives you an icon name like "01d"
    var iconName = data.weather[0].icon; // Example value: "01d"

    // Create a new image element
    var iconImg = new Image();

    // Set the base URL for your local image folder
    var baseUrl = "./weather_icons/";

    // Determine whether it's day or night (assuming 'd' for day and 'n' for night)
    var isDay = iconName.endsWith("d");

    // Construct the full icon URL based on the isDay flag
    var iconUrl = baseUrl + iconName + ".png";

    // Assuming "icon" is an HTML element where you want to display the icon
    var iconElement = document.getElementById("icon");

    // Clear any previous icon before adding the new one
    while (iconElement.firstChild) {
      iconElement.removeChild(iconElement.firstChild);
    }

    // Append the new icon image to the HTML element
    iconElement.appendChild(iconImg);
    console.log("Icon URL: ", iconUrl);

    icon.src = iconUrl;

    city.innerHTML = name + ", " + sys.country;
    temperature.innerHTML = (temp - 273.15).toFixed(0) + "&degc";
    humiditydisplay.innerHTML =  humidity + "%";
    pressureElement.innerHTML = pressure + " hPa";


    windSpeed.innerHTML =  speed + " m/s";
    direction.innerHTML =  deg + "°";
    visibilityElement.textContent =  +visibility/1000 + " km";
    weathercondition.innerHTML = capitalizedDescription;

    // Format the date as "Saturday 5, Aug"
    const options = { weekday: 'long', day: 'numeric', month: 'short' };
    let d = new Date(data.dt * 1000);
    date.innerHTML = d.toLocaleString(undefined, options);
    console.log("Function gettingWeather() called.");

  
    inputValue.value = "";

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `index.php?city=${encodeURIComponent(val)}`, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          // The data has been successfully saved to the server
          console.log("Weather data saved to the server.");
        } else {
          // There was an error while saving data
          console.error("Error saving weather data to the server.");
        }
      }
    };
    xhr.send();
  });
}

gettingWeather("Rhondda Cynon Taf");

button.addEventListener("click", function () {
  gettingWeather(inputValue.value);
});

inputValue.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    gettingWeather(inputValue.value);
  }
});


