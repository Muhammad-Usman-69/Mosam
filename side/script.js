//cresidentials
let key = "62cc8af97d144a42ad573800242006";
let city = "Multan";
let days = 7;

//fetching data from api
let p = fetch(
  `https://api.weatherapi.com/v1/forecast.json?key=${key}&q=${city}&days=${days}`
);

p.then((res) => {
  return res.json();
}).then((data) => {
  let city = data.location.name;
  let region = data.location.region;
  let country = data.location.country;
  let temp = data.current.temp_c;
  let feel = data.current.feelslike_c;
  let humidity = data.current.humidity;
  let conditionText = data.current.condition.text;
  let conditionIcon = data.current.condition.icon.replace(/64/gi, "128");

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

  document.getElementById("hourly").innerHTML  = "";

  //looping through today forecast
  hourForecasts.forEach((hourForecast) => {
    let epoch = hourForecast.time_epoch * 1000;

    //hour and period
    let hour = new Date(epoch).getHours();
    let period = hour < 12 ? "a.m." : "p.m.";
    let condition = hourForecast.condition.text;
    let image = hourForecast.condition.icon.replace(/64/gi, "128");

    document.getElementById("hourly").innerHTML += 
    `<div class="flex flex-col border-r-2 border-[#e4eaf2] last:border-none px-2" >
        <img src="https://${image}" style="background-color: transparent"
            />
        <p class="text-center">${hour + ` ` + period}</p>
        <p class="text-center">${condition}</p>
    </div>`;
  });

  /* console.log(city);
    console.log(region);
    console.log(country);
    console.log(temp);
    console.log(feel);
    console.log(conditionText);
    console.log(conditionIcon); */
});
