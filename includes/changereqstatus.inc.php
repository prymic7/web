<?php
    
    include_once "../includes/dbh.inc.php";

    
    
    $receiver_id = $_POST["receiver"];
    $sender_id = $_POST["sender"];
    $action = $_POST["action"];
    $response = ["status" => "success"];
    if($action === "reject"){
        $sql = "UPDATE friend_requests SET statusak = 'rejected' WHERE receiver_id = ? AND sender_id = ?";
    }

    if($action === "accept"){
        $sql = "UPDATE friend_requests SET statusak = 'accepted' WHERE receiver_id = ? AND sender_id = ?";
    }

    
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $receiver_id, $sender_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../profile.php?id=" . $nameid);
    echo json_encode($response);
    exit();