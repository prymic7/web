<?php
$response = ['status' => 'success'];
    
echo json_encode($response);
session_start();
require_once '../includes/dbh.inc.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $event_name = isset($_POST['title']) ? $_POST['title'] : '';
    $event_date = isset($_POST['date']) ? $_POST['date'] : '';
    $nameid = $_SESSION["id"];

    

    $sql = "INSERT INTO calendar_events (nameid, event_name, event_date) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($connection);
    
    if(!mysqli_stmt_prepare($stmt, $sql)){
    
        
        $error_message = mysqli_stmt_error($stmt);
        header("location: ../calendar.php?error=" . urlencode($error_message));
        exit();    
    }
   
    
    $event_date = date('Y-m-d', strtotime($event_date));
    mysqli_stmt_bind_param($stmt, "iss", $nameid, $event_name, $event_date);
    mysqli_stmt_execute($stmt); 
    mysqli_stmt_close($stmt);
    exit();

    
    
}








?>