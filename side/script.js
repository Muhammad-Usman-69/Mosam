//cresidentials
let key = "62cc8af97d144a42ad573800242006";
let city = "Multan";
let days = 6;

//fetching data from api
let p = fetch(
  `https://api.weatherapi.com/v1/forecast.json?key=${key}&q=${city}&days=${days}`
);

p.then((res) => {
  return res.json();
}).then((data) => {
  //today weather
  let city = data.location.name;
  let region = data.location.region;
  let country = data.location.country;
  let temp = data.current.temp_c;
  let feel = data.current.feelslike_c;
  let humidity = data.current.humidity;
  let conditionText = data.current.condition.text;
  let conditionIcon = data.current.condition.icon.replace(/64/gi, "128");
  let maximumTemp = data.forecast.forecastday[0].day.maxtemp_c;
  let minimumTemp = data.forecast.forecastday[0].day.mintemp_c;
  let wind = data.current.wind_kph;

  //assinging it
  document.getElementById("city").innerHTML = city;
  document.getElementById("region-country").innerHTML = region + " - " + country;
  document.getElementById("current-temp").innerHTML = temp + " &#8451;";
  document.getElementById("current-feel").innerHTML = feel + " &#8451;";
  document.getElementById("humidity").innerHTML = humidity;
  document.getElementById("condition").innerHTML = conditionText;
  document.getElementById("current-icon").innerHTML = conditionIcon;
  document.getElementById("maximum-temp").innerHTML = maximumTemp + " &#8451;";
  document.getElementById("minimum-temp").innerHTML = minimumTemp + " &#8451;";
  document.getElementById("wind").innerHTML = wind + " km/h";

  //hourly forecast data of today and tommowrow
  let today = data.forecast.forecastday[0].hour;
  let tommorow = data.forecast.forecastday[1].hour;
  let arr = today.concat(tommorow);
  let currentEpoch = Date.now();

  //for index
  let i = 0;
  //for arr
  let hourForecasts = [];

  //filtering after current epoch, removing one after before and getting only 6
  arr.forEach((hour) => {
    if (
      i % 2 === 1 &&
      hourForecasts.length < 6 &&
      hour.time_epoch * 1000 > currentEpoch
    ) {
      hourForecasts.push(hour);
    }

    i++;
  });

  //looping through today forecast
  hourForecasts.forEach((hourForecast) => {
    let epoch = hourForecast.time_epoch * 1000;

    //hour and period
    let hour = new Date(epoch).getHours();
    //making hour less than 12 and implementing am, pm
    let period = hour < 12 ? "a.m." : "p.m.";
    hour = hour < 12 ? hour : hour - 12;
    let condition = hourForecast.condition.text;
    let image = hourForecast.condition.icon.replace(/64/gi, "128");

    document.getElementById(
      "hourly"
    ).innerHTML += `<div class="flex flex-col border-r-2 border-[#e4eaf2] last:border-none " >
        <img src="https://${image}" style="background-color: transparent"
            />
        <p class="text-center">${hour + ` ` + period}</p>
        <p class="text-center">${condition}</p>
    </div>`;
  });

  // getting weekly forecast
  let weekForcasts = data.forecast.forecastday;

  weekForcasts.forEach((weekForcast) => {
    //multiplying for balancing
    let epoch = weekForcast.date_epoch * 1000;

    //making date and getting day from it
    let date = new Date(epoch);
    let dateNum = date.getDay();
    let daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    let day = daysOfWeek[dateNum];

    //if date is today
    if (dateNum == new Date(currentEpoch).getDay()) {
      day = "Today";
    }

    console.log(weekForcast);

    let condition = weekForcast.day.condition.text;
    let image = weekForcast.day.condition.icon.replace(/64/gi, "128");
    let maxTemp = weekForcast.day.maxtemp_c;
    let minTemp = weekForcast.day.mintemp_c;

    document.getElementById(
      "daily"
    ).innerHTML += `<div class="flex flex-col border-r-2 border-[#e4eaf2] last:border-none">
          <img src="https:${image}" style="background-color: transparent" />
          <p class="text-center">${day}</p>
          <p class="text-center">${condition}</p>
          <p class="text-center">Max: <span class="font-bold">${maxTemp}&#8451;</span></p>
          <p class="text-center">Min: <span class="font-bold">${minTemp}&#8451;</span></p>
        </div>`;
  });
});
