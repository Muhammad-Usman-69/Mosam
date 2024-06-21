<?php
//check if req is fost
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("location: /signup?error=Access denied. Please try again later");
    exit();
}

//adding db
include ("_dbconnect.php");

//taking diff values
$email = $_POST["email"];
$pass = $_POST["password"];
$city = $_POST["city"];

//check if any input is empty
if ($city == "" || $email == "" || $pass == "") {
    header("location: /signupup?error=Invalid cresidentials.");
    exit();
}

//check if email already exists
$sql = "SELECT * FROM `users` WHERE `email` = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);
if ($num != 0) {
    header("location: /signup?error=Email already in use");
    exit();
}

//inserting accont to db
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$city = htmlspecialchars($city, ENT_QUOTES, 'UTF-8');
$pass = htmlspecialchars($pass, ENT_QUOTES, 'UTF-8');
$pass_hash = password_hash($pass, PASSWORD_DEFAULT);
$sql = "INSERT INTO `users` (`email`, `password`, `city`) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sss", $email, $pass_hash, $city);
$result = mysqli_stmt_execute($stmt);

header("location: /login?alert=You have been signed up. Kindly log in");
exit();