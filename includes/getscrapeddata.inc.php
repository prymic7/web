<?php

// include_once '../header.php';
include_once 'functions.inc.php';
include_once 'dbh.inc.php';

$response = ["response" => "success"];
$rows = getScrapedData($connection);

echo json_encode($rows);



?>