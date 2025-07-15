<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require("./utils/functions.php");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(false, "Invalid request method");
    exit;
}

// Validate and sanitize inputs
$errors = [];

if (empty($_POST["username"])) {
    $errors[] = "Name is required";
} else {
    $name = test_input($_POST["username"]);
}

if (empty($_POST["email"])) {
    $errors[] = "Email is required";
} else {
    $email = test_input($_POST["email"]);
    if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/", $email)) {
        $errors[] = "Invalid email format";
    }
}

if (empty($_POST["password"])) {
    $errors[] = "Password is required";
} else {
    $password = test_input($_POST["password"]);
    if (!preg_match("/(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&*]).{8,}/", $password)) {
        $errors[] = "Password must have 8 characters, at least 1 special character, lowercase, uppercase character and digit";
    }
}

if (!empty($errors)) {
    sendResponse(false, $errors[0]);
    exit;
}

try {
    $pdo = connect();
    
    // Check if email already exists
    $query = "SELECT * FROM useraccounts WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        sendResponse(false, "Email already exists");
        exit;
    }
    
    // Hash password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $query = "INSERT INTO useraccounts (fullname, email, password) VALUES (:fullname, :email, :password)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":fullname", $name, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":password", $hashedPassword, PDO::PARAM_STR);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        sendResponse(true, "Registered Successfully");
        exit;
    } else {
        sendResponse(false, "User registration failed");
        exit;
    }
    
} catch (PDOException $e) {
    sendResponse(false, "Database error: " . $e->getMessage());
    exit;
}
?>