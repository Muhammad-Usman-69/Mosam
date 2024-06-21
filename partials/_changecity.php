<?php
//check if server method is post
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("location:/?error=Access denied");
    exit();
}

session_start();
//checking if logged
if (!isset($_SESSION["logged"]) && $_SESSION["logged"] != true) {
    header("location:/?error=Not logged in");
    exit();
}

include ("_dbconnect.php");


//taking id
if (!isset($_POST["city"])) {
    header("location: ../?error=Access Denied");
    exit();
}

$city = $_POST["city"];
$id = $_SESSION["id"];

$sql = "UPDATE `users` SET `city` = ? WHERE `id` = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $city, $id);
$bool = mysqli_stmt_execute($stmt);
if ($bool) {
    //reedirecting for normal
    header("location:/?alert=Updated Successfully");
    exit();
}