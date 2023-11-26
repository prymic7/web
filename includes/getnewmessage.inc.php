<?php
include_once 'dbh.inc.php';
include_once 'functions.inc.php';

$logged_id = $_POST["logged_id"];
$friend_id = $_POST["friend_id"];
$unseen_message = checkUnseenMessage($logged_id, $friend_id, $connection);

$response = $unseen_message;
    
    
echo json_encode($response);