fetch('display.php')
    .then(response => response.json())
    .then(data => {
        const weatherContainers = document.querySelectorAll('.box');

        data.forEach((day, index) => {
            const weatherContainer = weatherContainers[index];
            weatherContainer.querySelector('h2').textContent = day.Day;
            weatherContainer.querySelector('.data-item:nth-child(2)').textContent = `Date: ${day.Date}`;
            weatherContainer.querySelector('.data-item:nth-child(3)').textContent = `City: ${day.City}`;
            
            if (day.Temperature !== undefined) {
                weatherContainer.querySelector('.data-item:nth-child(4)').textContent = `Temperature: ${day.Temperature}°C`;
            } else {
                weatherContainer.querySelector('.data-item:nth-child(4)').textContent = '';
            }

            if (day.Humidity !== undefined) {
                weatherContainer.querySelector('.data-item:nth-child(5)').textContent = `Humidity: ${day.Humidity}%`;
            } else {
                weatherContainer.querySelector('.data-item:nth-child(5)').textContent = '';
            }

            if (day.Pressure !== undefined) {
                weatherContainer.querySelector('.data-item:nth-child(6)').textContent = `Pressure: ${day.Pressure} hPa`;
            } else {
                weatherContainer.querySelector('.data-item:nth-child(6)').textContent = 'No available data for this date';
            }

            // Set weather icon
            if (day.Icon !== undefined) {
                const iconContainer = weatherContainer.querySelector('.weather-icon');
                const iconUrl = `http://openweathermap.org/img/w/${day.Icon}.png`;
                const iconImg = document.createElement('img');
                iconImg.src = iconUrl;
                iconContainer.appendChild(iconImg);
            }

            // Set weather description
            if (day.Weather_Description !== undefined) {
                const descriptionParagraph = weatherContainer.querySelector('.weather-description');
                descriptionParagraph.textContent = day.Weather_Description;
            }
        });
    })
    .catch(error => console.error('Error fetching data:', error));