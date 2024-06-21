<?php
//check if server method is post
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("location:/?error=Access denied");
    exit();
}

//taking email and pass
$email = $_POST["email"];
$pass = $_POST["password"];

//checking if email and pass are empty
if ($email == "" || $pass == "") {
    header("location: /login?error=Invalid cresidentials");
    exit();
}

//verfiying email
include ("_dbconnect.php");

$sql = "SELECT * FROM `users` WHERE `email` = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);

//check if email is inavlid
if ($num == 0) {
    header("location: /login?error=Invalid email");
    exit();

}

//fetching name and id
$row = mysqli_fetch_assoc($result);
$id = $row["id"];
$city = $row["city"];

//verifying pass
if (password_verify($pass, $row["password"])) {
    session_start();
    $_SESSION["logged"] = true;
    $_SESSION["id"] = $id;
    $_SESSION["city"] = $city;
    header("location: /?alert=You are logged in");
    exit();
} else {
    //wrong pass
    header("location: /login?error=Wrong password");
    exit();
}