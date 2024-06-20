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
  let conditionText = data.current.condition.text;
  let conditionIcon = data.current.condition.icon.replace(/64/gi, "128");

  //hourly forecast data of today and tommowrow
  let today = data.forecast.forecastday[0].hour;
  let tommorow = data.forecast.forecastday[1].hour;
  let arr = today.concat(tommorow);
  let currentEpoch = Date.now() / 1000;

  //for index
  let i = 0;
  //for arr
  let hourForecast = [];

  //filtering after current epoch, removing one after before and getting only 6
  arr.forEach((hour) => {
    if (
      i % 2 === 1 &&
      hourForecast.length < 6 &&
      hour.time_epoch > currentEpoch
    ) {
      hourForecast.push(hour);
    }

    i++;
  });

  //looping through today forecast
  /* hourForecast.forEach((hour) => {
    
  }); */

  console.log(hourForecast);

  /* console.log(city);
    console.log(region);
    console.log(country);
    console.log(temp);
    console.log(feel);
    console.log(conditionText);
    console.log(conditionIcon); */
});
