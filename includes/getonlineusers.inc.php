<?php

    include_once 'dbh.inc.php';
    include_once 'functions.inc.php';

    
    $logged_id = $_POST["logged_id"];
    $returned = bringFriend($logged_id, $connection);

    

    $sql = "SELECT online_status FROM userinfo WHERE id = ?";

    
    $logged_id = $_POST["logged_id"];
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $logged_id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $online_status = mysqli_fetch_assoc($resultData);
    mysqli_stmt_close($stmt);
    
    
    $onlineFriendsId = array();
    
    
    foreach($returned as $friend){
        $online_status = getOnlineStatus($friend, $connection);
        
        

        if($online_status["online_status"] === 1){
            array_push($onlineFriendsId, $friend);
            
          
        } 
        
    
    }

    
    $response = json_encode($onlineFriendsId);
    echo $response;
    
    
    
    
?>