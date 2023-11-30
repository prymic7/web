<?php

if(isset($_POST["submit1"])){

    $username = $_POST["emname"];
    $password = $_POST["passwd"];


    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if(emptyInputLogin($username, $password) !== false){
        header("location: ../loginpage.php?error=emptyinput");
        
        exit();
    }

    if((correctCredentials($connection, $username, $password)) == false){
        header("location: ../loginpage.php?error=wrongcreds");
    }

} else {
    
    header("location: ../loginpage.php?error=jebat");
    
}
