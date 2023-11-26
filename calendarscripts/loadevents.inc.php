<?php
    session_start();
    include_once '../includes/dbh.inc.php';
    
    $nameid = $_SESSION['id'];
    

    $sql = "SELECT * FROM calendar_events WHERE nameid = ?";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "i", $nameid);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_all($resultData, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    echo json_encode($rows);
    exit();
?>