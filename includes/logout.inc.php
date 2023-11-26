<?php
    header("location: ../loginpage.php");

    include_once 'functions.inc.php';
    include_once 'dbh.inc.php';
    include_once '../header.php';
    $logged_id = $_SESSION['id'];
    updateOnlineStatus(0, $logged_id, $connection);

    
    session_destroy();

    

?>