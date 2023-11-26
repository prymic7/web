
<?php
    include_once 'header.php';
    if(isset($_SESSION['id'])){
        $id = $_SESSION["id"];
    }
    
    if(isset($_SESSION["username"])){
        header("location: profile.php?id=$id");
    }
?>
        <div class="registertext1">
            
            <h3>Registrace:</h3>
        </div>
    
        
        <div class="tabulka-registrace">
            <form action="includes/registering.inc.php" method="post">
                <label>Zadejte svoje uživatelské jméno:</label><br>
                <input type="text" name="username"><br>
                <label>Zadejte svoje křestní jméno:</label><br>
                <input type="text" name="first_name"><br>
                <label>Zadejte svoje přijímení:</label><br>
                <input type="text" name="second_name"><br>
                <label>Zadejte svoje heslo:</label><br>
                <input type="password" name="passwd1"><br>
                <label>Heslo znovu:</label><br>
                <input type="password" name="passwd2"><br>
                <label>Zadejte název nového emailu:</label><br>
                <input type="email" name="email"><br>
                <!-- <label>Datum narození:</label><br>
                <input type="date" id="start" name="dateofbirth"> -->
                
                
            <input type="submit" name="submit" class="submitbutton">
            <?php
                if(isset($_GET["error"])){
                    if($_GET["error"] == "emptyinput"){
                        echo "<p class='error_message'>Vyplňte všechny pole.</p>";
                    }
                    else if($_GET["error"] == "invalidusername"){
                        echo "<p class='error_message'>Vaše uživatelské jméno obsahuje neplatný znak.</p>";
                    }
                    else if($_GET["error"] == "invalidemail"){
                        echo "<p class='error_message'>Zadejte prosím správný email.</p>";
                    }
                    else if($_GET["error"] == "passwdMatch"){
                        echo "<p class='error_message'>Vaše hesla se neshodují.</p>";
                    }
                    else if($_GET["error"] == "usernameexists"){
                        echo "<p class='error_message'>Email nebo uživatelské jméno je již obsazeno.</p>";
                    }
                    else if($_GET["error"] == "toolongusername"){
                        echo "<p class='error_message'>Zvolte kratší jméno. Jméno je příliš dlouhé.</p>";
                    }
                    else if($_GET["error"] == "stmtfailed"){
                        echo "<p class='error_message'>Jejda! Něco se pokazilo.</p>";
                    }
                    
                }
               

            ?>
            </form> 
        </div>
        
        
        
        
    

        
        

<?php
    include_once 'footer.php';
?>