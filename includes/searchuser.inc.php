<?php
    session_start();
    include_once 'dbh.inc.php';

    
    $nameid = $_SESSION['id'];
   
    $user_search = isset($_POST['search']) ? $_POST['search'] : '';
    $response = ['status' => 'success'];
    

    $sql = "SELECT * FROM userinfo WHERE username LIKE ?";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    $user_search = $user_search . "%"; 
    mysqli_stmt_bind_param($stmt, "s", $user_search);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_all($resultData, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    echo json_encode($rows);
    
    exit();
    

    

?>