<?php


include_once 'functions.inc.php';

include_once 'dbh.inc.php';


if($_SERVER["REQUEST_METHOD"] === "POST") {
    //
   

}

$id_logged = $_POST["logged_id"];
    

$rows = getChats($id_logged, $connection);


$users_slabs = array();

foreach($rows as $row){
    $sender_id = $row["sender_id"];
    $user_row = loadUserInfo($row["sender_id"], $connection);
    $text_last_message = getLastMessage($id_logged, $sender_id, $connection);
    $username = $user_row["username"];
    $profileimage = $user_row["profileimage"];
    $id = $user_row["id"];
    $one_slab = [$profileimage, $username, $text_last_message["message_text"], $id];
    $users_slabs[] = $one_slab;
    
}
echo json_encode($users_slabs);




