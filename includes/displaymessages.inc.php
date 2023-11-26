<?php
include_once 'dbh.inc.php';
include_once 'functions.inc.php';

$friend_id = $_POST["friend_id"];
$logged_id = $_POST["logged_id"];
$response = ["status" => "success"];
$sql = "SELECT * FROM user_messages WHERE (receiver_id = ? AND sender_id = ?) OR (sender_id = ? AND receiver_id = ?)";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }

    
    mysqli_stmt_bind_param($stmt, "iiii", $logged_id, $friend_id, $logged_id, $friend_id);
    mysqli_stmt_execute($stmt);
   

    $resultData = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_all($resultData);

    mysqli_stmt_close($stmt);



        
    
// echo json_encode($rows);
echo json_encode($rows);

//PO ROZKLIKNUTI PRITELE CHCI NACIST ZPRAVY