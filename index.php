<?php
session_start();

if (isset($_SESSION["logged"]) && $_SESSION["logged"] == true) {
  include ("partials/_dbconnect.php");

  $sql = "SELECT * FROM `users` WHERE `id` = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $_SESSION["id"]);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $city = $row["city"];
} else {
  $city = "Multan";
}

if (isset($_GET["city"]) && $_GET["city"] != "") {
  $city = $_GET["city"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mosam - Weather APP</title>
  <meta name="author" content="Muhammad Usman" />
  <link rel="stylesheet" href="side/style.css" />
  <link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />
</head>

<body class="bg-[#e4eaf2] text-gray-800 text-sm">
  <header class="grid grid-cols-2 bg-white p-4 md:grid-cols-3 gap-4">
    <a href="/" class="flex items-center space-x-3">
      <img src="images/logo.png" class="w-12 h-12" />
      <p class="text-lg">Mosam</p>
    </a>
    <div class="place-items-center grid row-start-2 row-end-2 col-span-2 md:col-span-1 md:col-start-2 md:row-start-1">
      <?php
      if (!isset($_SESSION["logged"])) {
        echo '<form action="" class="border-2 border-[#e4eaf2] flex flex-row" method="GET">';
      } else {
        echo '<form action="partials/_changecity" class="border-2 border-[#e4eaf2] flex flex-row" method="POST">';
      }
      ?>
        <input type="text" name="city" id="city" class="p-4 outline-none placeholder:italic" placeholder="Real Madrid"
        oninput="checkCity()" minlength="2" />
        <button type="submit" class="px-4 bg-[#1ab5ed] text-white" id="update" disabled>Update</button>
      </form>
    </div>
    <div class="flex items-center space-x-8 justify-end">
      <div class="items-center space-x-2 flex">
        <img src="images/city.png" alt="" class="w-7 h-7" />
        <p class="capitalize"><?php echo $city; ?></p>
      </div>
      <div>
        <?php
        if (isset($_SESSION["logged"]) && $_SESSION["logged"] == true) {
          echo '<a href="logout" class="text-lg underline text-[#ffc106]">Logout</a>';
        } else {
          echo '<a href="login" class="text-lg underline text-[#ffc106]">Login</a>';
        }
        ?>
      </div>
    </div>
  </header>
  <!-- all container -->
  <div class="space-y-4 p-4 md:p-8 md:space-y-8 grid place-items-center w-full">

    <!-- basic -->
    <div
      class="flex flex-col space-y-12 sm:justify-between sm:flex-row w-full lg:w-[800px] bg-white rounded-md p-8 sm:space-y-0">
      <div class="flex flex-col space-y-12 m:w-48">
        <div class="flex  justify-center w-full">
          <img src="" alt="" style="background-color: transparent" id="current-icon" width="200" height="200" />
        </div>
        <ul class="text-gray-600 text-base">
          <li class="flex justify-between space-x-3">
            <p class="">Condition:</p>
            <p class="font-bold text-end max-w-36" id="condition"></p>
          </li>
          <li class="flex justify-between space-x-3">
            <p class="">Maximun:</p>
            <p class="font-bold" id="maximum-temp"></p>
          </li>
          <li class="flex justify-between space-x-3">
            <p class="">Minimum:</p>
            <p class="font-bold" id="minimum-temp"></p>
          </li>
          <li class="flex justify-between space-x-3">
            <p class="">Wind:</p>
            <p class="font-bold" id="wind"></p>
          </li>
          <li class="flex justify-between space-x-3">
            <p class="">Sunrise:</p>
            <p class="font-bold" id="sunrise"></p>
          </li>
          <li class="flex justify-between space-x-3">
            <p class="">Sunset:</p>
            <p class="font-bold" id="sunset"></p>
          </li>
          <li class="flex justify-between space-x-3">
            <p class="">Last Update:</p>
            <p class="font-bold" id="last-update"></p>
          </li>
        </ul>
      </div>
      <div class="flex flex-col space-y-12">
        <div class="space-y-4">
          <p class="text-[32px] flex items-center justify-end">
            <img src="images/location.jpg" style="width: 32px; height: 32px" />
            <span id="city-name"></span>
          </p>
          <p class="text-gray-400 text-end" id="region-country"></p>
        </div>
        <div class="space-y-4">
          <p class="text-[32px] flex items-center justify-end">
            <img src="images/images.png" style="width: 32px; height: 32px" />
            <span id="current-temp"></span>
          </p>
          <p class="text-end text-gray-400">Feels Like <span id="current-feel"></span></p>
        </div>
        <div class="space-y-4">
          <p class="text-[32px] flex items-center justify-end space-x-2">
            <img src="images/humidity.png" style="width: 32px; height: 32px" />
            <span id="humidity"></span>
          </p>
          <p class="text-end text-gray-400">Humidity</p>
        </div>
      </div>
    </div>

    <div class="w-full lg:w-[800px] bg-white rounded-md pb-4 md:pb-8">
      <p class="text-[24px] p-8 text-gray-800">Hourly Forecast</p>
      <div class="grid grid-cols-3 md:grid-cols-6 text-gray-400 text-base gap-2 px-2" id="hourly"></div>
    </div>

    <div class="w-full lg:w-[800px] bg-white rounded-md pb-4 md:pb-8">
      <p class="text-[24px] p-8 text-gray-800">Daily Forecast</p>
      <div class="grid grid-cols-3 md:grid-cols-6 text-gray-400 text-base gap-2 px-2" id="daily"></div>
    </div>
  </div>

  <script>
    let city = "<?php echo $city; ?>";
  </script>
  <script src="side/script.js"></script>
</body>

</html>