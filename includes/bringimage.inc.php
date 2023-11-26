<?php
    include_once "../includes/functions.inc.php";
    include_once "../includes/dbh.inc.php";


    session_start();
    $id;
    if(isset($_POST["idecko"])){
        $id = $_POST["idecko"];
    }
    


    $sql = "SELECT * FROM userinfo WHERE id = ?";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($resultData);
    mysqli_stmt_close($stmt);
    $result = $row['profileimage'];
    
    


    echo json_encode($result);