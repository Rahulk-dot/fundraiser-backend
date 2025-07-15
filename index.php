<?php
// Load any necessary utility functions or configurations
require("./utils/function.php");

// Basic status check for the backend
header("Content-Type: application/json");

// Response to indicate that the backend is running
$response = [
    "success" => true,
    "message" => "Fundraiser backend is up and running!",
    "timestamp" => date("Y-m-d H:i:s")
];

echo json_encode($response);
