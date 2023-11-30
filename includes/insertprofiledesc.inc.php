<?php
include_once "../includes/functions.inc.php";
include_once "../includes/dbh.inc.php"; 

session_start();

$desc_value;
$logged_id;
if(isset($_POST["desc"]))
{
    $desc_value = $_POST["desc"];
}
if(isset($_POST["id_logged"]))
{
    $logged_id = $_POST["id_logged"];
}
$sql = "INSERT INTO profiledesc (user_id, user_desc) VALUES (?, ?) ON DUPLICATE KEY UPDATE user_desc = ?";

$stmt = mysqli_stmt_init($connection);
if(!mysqli_stmt_prepare($stmt, $sql)){
    header("location: ../registerpage.php?error=stmtfailed");
    exit();
}
mysqli_stmt_bind_param($stmt, "iss", $logged_id, $desc_value, $desc_value);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
exit();

