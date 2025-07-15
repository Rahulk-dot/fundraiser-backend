<?php

require("./utils/functions.php");

if (!$_SERVER["REQUEST_METHOD"] == "GET") {
    sendResponse(false, "Invalid request method");
}else{
   


    $pdo = connect();

    $query = "SELECT * FROM campaigns WHERE totalgoal <= currentamountraised";
    $stmt = $pdo->prepare($query);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    $jsonResult = json_encode($result);

    
    header('Content-Type: application/json');
    echo $jsonResult;

    
}


?>