<?php

require("./utils/functions.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    sendResponse(false, "Invalid request method");
}

if (!isset($_POST["token"])) {
    http_response_code(400);
    sendResponse(false, "Token is required");
}

$token = $_POST["token"];

$pdo = connect();

$query = "SELECT * FROM useraccounts WHERE token = :token";
$stmt = $pdo->prepare($query);
$stmt->bindParam("token", $token, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    sendResponse(true, "Success", ["user" => $user]);
}

http_response_code(401);
sendResponse(false, "Please Log In again!");
