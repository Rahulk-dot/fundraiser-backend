<?php
require("./utils/functions.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    sendResponse(false, "Invalid request method");
}

if (!isset($_POST["user_id"])) {
    sendResponse(false, "user_id is required");
}
if (!isset($_POST["eventname"])) {
    sendResponse(false, "eventname is required");
}
if (!isset($_POST["date"])) {
    sendResponse(false, "date is required");
}
if (!isset($_POST["totalgoal"])) {
    sendResponse(false, "totalgoal is required");
}
if (!isset($_POST["description"])) {
    sendResponse(false, "description is required");
}

$user_id = $_POST["user_id"];
$eventname = $_POST["eventname"];
$date =$_POST["date"];
$totalgoal = $_POST["totalgoal"];
$description =$_POST["description"];

$pdo = connect();

$query = "INSERT INTO campaigns(eventname,date, description,totalgoal, currentamountraised, user_id, status) VALUES(:eventname, :date, :description, :totalgoal,0, :user_id, 0)";
$stmt = $pdo->prepare($query);
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_STR);
$stmt->bindParam(":eventname", $eventname, PDO::PARAM_STR);
$stmt->bindParam(":date", $date, PDO::PARAM_STR);
$stmt->bindParam(":description", $description, PDO::PARAM_STR);
$stmt->bindParam(":totalgoal", $totalgoal, PDO::PARAM_STR);
$stmt->execute();


if ($stmt->rowCount() > 0) {
    sendResponse(true, "Data Inserted");
}else{
    sendResponse(false, "Data not inserted");
}



?>