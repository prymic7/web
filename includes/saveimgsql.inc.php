<?php
    include_once "../includes/functions.inc.php";
    include_once "../includes/dbh.inc.php";


    session_start();

    
    $nameid = $_SESSION['id'];
   
    if(isset($_FILES["pp"]) && isset($_POST["submit"])){
        $img_temp_path_server = $_FILES['pp']['tmp_name'];
        $img_user_computer_path = $_FILES['pp']['name'];
        $img_error = $_FILES['pp']['error'];
        $img_type = $_FILES['pp']['type'];
        $img_size = $_FILES['pp']['size'];
        
        if($img_error === 0){
            if($img_size > 125000) {
                
                header("location: ../profile.php?id=$nameid&error=largesize");
                exit();
            } else {
                $img_ex = pathinfo($img_user_computer_path, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
                $allowed_exs = array("jpg", "png", "jpeg");

                if(in_array($img_ex_lc, $allowed_exs)){
                    $new_image_name = uniqid("IMG-", true). '.' .$img_ex_lc;
                    $new_upload_file = "../upload_image/".$new_image_name;
                    move_uploaded_file($img_temp_path_server, $new_upload_file);
                    $imageBinary = file_get_contents($new_upload_file);
                   
                    $imagebase64 = base64_encode($imageBinary);
                
                    $sql = "UPDATE userinfo SET profileimage = ? WHERE id = ?";
                    $stmt = mysqli_stmt_init($connection);
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("location: ../registerpage.php?error=stmtfailed");
                        exit();
                    }
                    mysqli_stmt_bind_param($stmt, "si", $imagebase64, $nameid);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    header("location: ../profile.php?id=" . $nameid);
                    exit();
                    


                    
                } else {
                    
                    header("location: ../profile.php?id=$nameid&error=wrongfiletype");
                    exit();
                }
            }
        } else {
            
            header("location: ../profile.php?id=$nameid&error=");
            exit();
        }
    }


