<?php
    $gocreate = true;
    if(isset($_POST["submit"])){
        
        $name = $_POST["username"];
        $first_name = $_POST["first_name"];
        $second_name = $_POST["second_name"];
        $pass1 = $_POST["passwd1"];
        $pass2 = $_POST["passwd2"];
        $email = $_POST["email"];
        $date = $_POST["dateofbirth"];
        


        require_once 'dbh.inc.php';
        require_once 'functions.inc.php';

        if(emptyInputSignup($name, $first_name, $second_name, $pass1, $pass2, $email, $date) !== false){
            header("location: ../registerpage.php?error=emptyinput");
            $gocreate = false;
            exit();
        }

        if(invalidUsername($name) !== false){
            header("location: ../registerpage.php?error=invalidusername");
            $gocreate = false;
            exit();
        }

        if(invalidEmail($email) !== false){
            header("location: ../registerpage.php?error=invalidemail");
            $gocreate = false;
            exit();
        }

        if(passwdMatch($pass1, $pass2) !== false){
            header("location: ../registerpage.php?error=passwdMatch");
            $gocreate = false;
            exit();
        }

        if(usernameExists($connection, $name, $email) !== false){
            header("location: ../registerpage.php?error=usernameexists");
            $gocreate = false;
            exit();
        }
        
        if(tooLongUsername($name) !== false){
            header("location: ../registerpage.php?error=toolongusername");
            $gocreate = false;
            exit();
        }

        if($gocreate){
            
            $id = createUser($connection, $name, $first_name, $second_name, $email, $pass1);
            header("location: ../loginpage.php");
        }
        

    } else {
        header("location: ../registerpage.php");
        exit();
    }
    

?>