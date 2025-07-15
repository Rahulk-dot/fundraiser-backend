<?php
require("./utils/functions.php");

if (!$_SERVER["REQUEST_METHOD"] == "GET") {
    sendResponse(false, "Invalid request method");
}else{
   


    $pdo = connect();

    $query = "SELECT amount, fullname, c.eventname, donated_at FROM (donations d LEFT JOIN useraccounts ua ON d.user_id = ua.user_id) JOIN campaigns c ON d.campaign_id = c.campaign_id WHERE donated_at >= CURRENT_DATE - INTERVAL '7 days'";
    $stmt = $pdo->prepare($query);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    $jsonResult = json_encode($result);

    
    header('Content-Type: application/json');
    echo $jsonResult;

    
}


?>