<?php
    session_start();
    include_once '../includes/dbh.inc.php';
    
    $nameid = $_SESSION['id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
        $event_date = isset($_POST['date']) ? $_POST['date'] : '';
        $response = ['status' => 'success'];
        echo json_encode($response);

        $sql = "DELETE FROM calendar_events WHERE nameid = ? AND event_date = ?";
        $stmt = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../registerpage.php?error=stmtfailed");
            exit();
        }
        
        mysqli_stmt_bind_param($stmt, "is", $nameid, $event_date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    exit();
?>