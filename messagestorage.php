<?php
include_once 'includes/functions.inc.php';
include_once 'includes/dbh.inc.php';



$text;
if(isset($_POST["message-text-input"])){
    $response = ["status" => "success"];
    $sender_id = $_POST["sender_id"];
    $receiver_id = $_POST["receiver_id"];
    $text = $_POST["message-text-input"];

    $timestamp = date("Y-m-d H:i:s");
   
}



    $sql = "INSERT INTO user_messages (sender_id, receiver_id, message_text, timestamp) VALUES(?,?,?, CURRENT_TIMESTAMP)";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "iis", $sender_id, $receiver_id, $text);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    
echo json_encode($response);


?>