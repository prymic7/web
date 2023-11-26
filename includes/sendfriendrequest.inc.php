<?php
    include_once "../includes/functions.inc.php";
    include_once "../includes/dbh.inc.php";


    session_start();

    $response = ['status' => 'success'];
    $sql = "INSERT INTO friend_requests (sender_id, receiver_id, statusak, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    $status = "pending";
    $senderID = $_POST["id_sender"];
    $receiverID = $_POST["id_receiver"];
    mysqli_stmt_bind_param($stmt, "iis", $senderID, $receiverID, $status);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo json_encode($response);