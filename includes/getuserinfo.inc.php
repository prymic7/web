<?php
include_once 'functions.inc.php';
include_once 'dbh.inc.php';



$id = $_POST["id"];
$rows = loadUserInfo($id, $connection);

echo json_encode($rows);