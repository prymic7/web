<?php
include_once "../includes/functions.inc.php";
include_once "../includes/dbh.inc.php"; 
$logged_id = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $logged_id = $_POST["user_id"];
}

if ($logged_id !== null) {
    $sql = "SELECT user_desc FROM profiledesc WHERE user_id = ?";

    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../registerpage.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $logged_id);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    
    // Fetch the data as an associative array
    $row = mysqli_fetch_assoc($resultData);

    // Check if a row was found
    if ($row) {
        // Output the JSON-encoded data
        echo json_encode($row);
    } else {
        // No data found
        echo json_encode(["error" => "No data found for the given user ID"]);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // No user ID provided
    echo json_encode(["error" => "No user ID provided"]);
}

// Close the database connection
mysqli_close($connection);

