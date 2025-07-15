<?php
require("./utils/functions.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    sendResponse(false, "Invalid request method");
}

if (!isset($_POST["user_id"])) {
    sendResponse(false, "user_id is required");
}
if (!isset($_POST["campaign_id"])) {
    sendResponse(false, "campaign_id is required");
}
if (!isset($_POST["amount"])) {
    sendResponse(false, "amount is required");
}


$user_id = $_POST["user_id"];
$campaign_id = $_POST["campaign_id"];
$amount =$_POST["amount"];


$pdo = connect();

$query = "INSERT INTO donations(amount, user_id, campaign_id, donated_at) VALUES(:amount, :user_id, :campaign_id, CURRENT_DATE)";
$stmt = $pdo->prepare($query);
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_STR);
$stmt->bindParam(":campaign_id", $campaign_id, PDO::PARAM_STR);
$stmt->bindParam(":amount", $amount, PDO::PARAM_INT);


$stmt->execute();
if ($stmt->rowCount() == 0) {
    sendResponse(false, "Not inserted");
}


$query = "UPDATE campaigns SET currentamountraised = currentamountraised + :amount WHERE campaign_id = :campaign_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(":amount", $amount, PDO::PARAM_INT);
$stmt->bindParam(":campaign_id", $campaign_id, PDO::PARAM_STR);

   
$stmt->execute();

if ($stmt->rowCount() > 0) {
    sendResponse(true, "Successfully Raised");
}

sendResponse(false, "Not Donated");
