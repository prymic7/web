<?php
    $db_host = "localhost";
    $db_username = "root";
    $db_passwd = "";
    $db_name = "login";


    $connection = mysqli_connect($db_host, $db_username, $db_passwd, $db_name);


    if(mysqli_connect_error()){
        echo mysqli_connect_error();
        exit;
    }

?>
