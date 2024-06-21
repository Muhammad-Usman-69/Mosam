<?php include ("partials/_dbconnect.php"); ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <link href="./side/style.css" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="images/logo.jfif" type="image/x-icon" />
  <title>Bay Bakery</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
</head>

<body class="open-sans bg-gray-50 relative">
  <!-- alert and error  -->
  <div class="alert transition-all duration-200">
    <?php
    if (isset($_GET["error"])) {
      echo '<div class="bg-red-100 border border-red-400 hover:bg-red-50 text-red-700 px-4 py-3 rounded space-x-4 flex items-center justify-between fixed bottom-5 right-5 ml-5 transition-all duration-200 z-20"
        role="alert">
                <strong class="font-bold text-sm">' . $_GET["error"] . '.</strong>
                <span onclick="hideAlert(this);">
                    <svg class="fill-current h-6 w-6 text-red-600 border-2 border-red-700 rounded-full" role="button"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>';
    }
    ?>
  </div>
  <a href="/"
    class="text-white absolute top-4 left-4 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  px-5 py-2.5 text-center">
    Home
  </a>
  <form class="max-w-md mx-auto min-h-screen flex flex-col items-center justify-center" method="post"
    action="partials/_signup.php">
    <div class="relative z-0 w-full mb-5 group">
      <input type="text" name="city" id="city"
        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
        placeholder=" " required minlength="2" oninput="checkCity(this.value)" />
      <label for="city"
        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Your
        City</label>
    </div>
    <p class="text-sm hidden text-red-600 border-red-800 border-b-2" id="indicator">City Not Available</p>
    <div class="text-sm hidden text-green-600 border-green-700 border-b-2" id="auto-indicator">You mean <span
        id="auto-city"></span>? <button class="font-bold" onclick="confirmCity()">Yes</button></div>
    <div class="relative z-0 w-full mb-5 group">
      <input type="email" name="email" id="email"
        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
        placeholder=" " required minlength="10" autocomplete="username" />
      <label for="email"
        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email
        address</label>
    </div>
    <div class="relative z-0 w-full mb-5 group">
      <input type="password" name="password" id="password"
        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
        placeholder=" " required autocomplete="new-password" />
      <label for="password"
        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
    </div>
    <p id="helper-text-explanation" class="mb-5 text-sm text-gray-500 dark:text-gray-400">Already a user? <a
        href="login" class="font-medium text-blue-600 hover:underline">Log in</a>.</p>
    <button type="submit"
      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
      id="submit" disabled>
      Sign up
    </button>
  </form>
  <script>
    //alert
    function hideAlert(element) {
      //hiding alert
      element.parentNode.classList.add("opacity-0");

      //removing alert
      setTimeout(() => {
        element.parentNode.remove();
      }, 200);
    }

    //initializing for name
    let city;

    //check if city exist
    async function checkCity() {

      //disabling submit button and showign wrong alerts
      document.getElementById("submit").disabled = true;
      document.getElementById("indicator").classList.remove("hidden");
      document.getElementById("auto-indicator").classList.add("hidden");

      //fetching data from api
      let key = "62cc8af97d144a42ad573800242006";
      let city = document.getElementById("city").value;
      let p = fetch(
        `https://api.weatherapi.com/v1/forecast.json?key=${key}&q=${city}`
      );
      p.then(res => {
        return res.json();
      }).then(data => {

        if (data.location != undefined) {
          city = data.location.name;

          //showing auto correct and hiding wrong indication
          document.getElementById("indicator").classList.add("hidden");
          document.getElementById("auto-city").innerHTML = city;
          document.getElementById("auto-indicator").classList.remove("hidden");


          //enabling submit button
          document.getElementById("submit").disabled = false;
        }

        //if input is clear 
        if (city == "") {
          document.getElementById("indicator").classList.add("hidden");
          document.getElementById("auto-indicator").classList.add("hidden");
          document.getElementById("submit").disabled = true;
        }
      })
    }

    function confirmCity() {
      document.getElementById("city").value = document.getElementById("auto-city").innerHTML;
      document.getElementById("auto-indicator").classList.add("hidden");
    }
  </script>
</body>

</html>