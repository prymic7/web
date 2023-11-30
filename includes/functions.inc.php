<?php

//register
function emptyInputSignup($name, $first_name, $second_name, $pass1, $pass2, $email, $date){
    $result = false;
    if(empty($name || $first_name || $second_name || $pass1 || $email || $date)){
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidUsername($name){
    $result = false;
    if(!preg_match("/^[a-zA-Z0-9]*$/", $name)){
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidEmail($email){
    $result = false;
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function passwdMatch($pass1, $pass2){
    $result = false;
    if($pass1 !== $pass2){
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function tooLongUsername($name){
    $result = false;
    if(strlen($name) > 16){
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function createUser($connection, $username, $first_name, $second_name, $email, $pass1){
    $sql = "INSERT INTO userinfo (username, first_name, second_name, date, email, password) VALUES (?, ?, ?, DATE(NOW()), ?, ?)";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }

    $hashedPass = password_hash($pass1, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "sssss", $username, $first_name, $second_name, $email, $hashedPass);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $_SESSION['allowedAccess'] = true;
    $userId = mysqli_insert_id($connection);
    return $userId;
}

function usernameExists($connection, $username, $email){
    $sql = "SELECT * FROM userinfo WHERE username = ? OR email = ?;";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        return $row;
    } else {

        $result = false;
        return $result;
    }
    mysqli_stmt_close($stmt);

}

//login
function emptyInputLogin($username, $password){
    if(empty($username || $password)){
        return true;
    }
    return false;
}

function correctCredentials($connection, $username, $password){
    $usernameExists = usernameExists($connection, $username, $username);
    if($usernameExists === false){
        header("location: ../loginpage.php?error=wronglogin");
    }
    // $hashedPass = password_hash($password, PASSWORD_DEFAULT);
    $hashedPass = $usernameExists["password"];
    $checkPass = password_verify($password, $hashedPass);
    if($checkPass === false){
        header("location: ../loginpage.php?error=wronglogin");
    }
    else if($checkPass){
        if(isset($_COOKIE["redirect"]) && $_COOKIE["redirect"] == "calendar"){
            header("location: ../calendar.php");
            setcookie("redirect", "calendar", time() - 3600, "/");
            
        } else {
            header("location: ../home.php");
            
        }
        
        session_start();
        $_SESSION["id"] = $usernameExists["id"];
        $_SESSION["username"] = $usernameExists["username"];

        $logged_id = $_SESSION["id"];
        updateOnlineStatus(1, $logged_id, $connection);
        require_once '../loginpage.php';
        exit();
    }
}



function getUsersEvents($connection, $nameid){
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
    return $rows;
    
}

function loadUserInfo($id, $connection){
    $sql = "SELECT * FROM userinfo WHERE id = ?";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_assoc($resultData);
    mysqli_stmt_close($stmt);
    return $rows;
}

function checkEmptyImage($id, $connection){
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
    
    if(empty($row['profileimage'])){
        return false;
    } else {
        $result = $row['profileimage'];
        return $result;
    }
}


function loadFriendRequests($logged_id, $connection){
    $sql = "SELECT * FROM friend_requests WHERE receiver_id = ?";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $logged_id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_all($resultData, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
    
}

function bringStatus($receiver_id, $sender_id, $connection){
    $sql = "SELECT * FROM friend_requests WHERE receiver_id = ? AND sender_id = ?";

    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $receiver_id, $sender_id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($resultData);
    mysqli_stmt_close($stmt);
    
    return $row;
}

function isFriend($logged_id, $want_id, $connection){
    $sql = "SELECT * FROM friend_requests WHERE receiver_id = ? AND sender_id = ?";

    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $logged_id, $want_id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $first_att = mysqli_fetch_assoc($resultData);
    mysqli_stmt_close($stmt);

    $sql = "SELECT * FROM friend_requests WHERE receiver_id = ? AND sender_id = ?";

    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $want_id, $logged_id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $second_att = mysqli_fetch_assoc($resultData);
    mysqli_stmt_close($stmt);

    if($first_att && $first_att["statusak"] === "accepted" || $second_att && $second_att["statusak"] === "accepted"){
        return "accepted";
    } 

    if($first_att && $first_att["statusak"] === "pending" || $second_att && $second_att["statusak"] === "pending"){
        return "pending";
    } 

    if($first_att && $first_att["statusak"] === "rejected" || $second_att && $second_att["statusak"] === "rejected"){
        return "rejected";
    } 
    return false;
        
    
}

function bringFriend($logged_id, $connection){
    $sql = "SELECT * FROM friend_requests WHERE receiver_id = ?";

    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $logged_id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $array1 = array();
    $rows = mysqli_fetch_all($resultData, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    $friend_id = 0;
    
    foreach($rows as $row){
        if($row["statusak"] === "accepted"){
            $friend_id = $row["sender_id"];
            $array1[] = $friend_id;
        }
        
    }

    
    
    $sql = "SELECT * FROM friend_requests WHERE sender_id = ?";

    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $logged_id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_all($resultData, MYSQLI_ASSOC);
    
    mysqli_stmt_close($stmt);

    
    foreach($rows as $row){
        if($row["statusak"] === "accepted"){
            $friend_id = $row["receiver_id"];
            $array1[] = $friend_id;
        }
    }

    return $array1;  
}


function updateOnlineStatus($newStatus, $id, $connection){
    $sql = "UPDATE userinfo SET online_status = ? WHERE id = ?";

    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $newStatus, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function getOnlineStatus($id, $connection){
    $sql = "SELECT online_status FROM userinfo WHERE id = ?";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $online_status = mysqli_fetch_assoc($resultData);
    mysqli_stmt_close($stmt);

    return $online_status;

    
}

function checkUnseenMessage($logged_id, $friend_id, $connection){
    $sql = "SELECT * FROM user_messages WHERE receiver_id = ? AND sender_id = ? AND seen = 0";
    
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $logged_id, $friend_id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_all($resultData);

    if($rows){
        $sql = "UPDATE user_messages SET seen = 1 WHERE (receiver_id = ? AND sender_id = ?) OR (sender_id = ? AND receiver_id = ?) AND seen = 0";

        $stmt = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../registerpage.php?error=stmtfailed");
            exit();
        }


        mysqli_stmt_bind_param($stmt, "iiii", $logged_id, $friend_id, $logged_id, $friend_id);
        mysqli_stmt_execute($stmt);


        return true;
    } else {
        return false;
    }
}

function getScrapedData($connection){
    $sql = "SELECT * FROM scraped_data";
    
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }
    
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_all($resultData, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $rows;
}

function getChats($logged_id, $connection){
    $sql = "SELECT DISTINCT receiver_id, sender_id FROM user_messages WHERE receiver_id = ?";

    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $logged_id);
    
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_all($resultData, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $rows;

}

function getLastMessage($sender_id, $receiver_id, $connection){
    $sql = "SELECT message_text FROM user_messages WHERE (sender_id = ? AND receiver_id = ?) OR (receiver_id = ? AND sender_id = ?) ORDER BY timestamp DESC LIMIT 1";

    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "iiii", $sender_id, $receiver_id, $sender_id, $receiver_id);
    
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($resultData);

    mysqli_stmt_close($stmt);

    return $row;

}

