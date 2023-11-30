
<?php
    include_once 'header.php';
    if(isset($_SESSION["id"])){
        $id = $_SESSION["id"];
    }
    
    
?>
    
    
    
        <div class="tabulka-login">
            <form action="includes/checkcreds.inc.php" method="post" class="loginform">
                <label>Zadejte svoje přihlašovací jméno nebo email:</label><br>
                <input type="text" name="emname"><br>
                <label>Zadejte svoje heslo:</label><br>
                <input type="password" name="passwd"><br>
            <input type="submit" name="submit1" class="submitbutton">
            <?php
                if(isset($_GET["error"])){
                    if($_GET["error"] == "wrongcreds"){
                        echo "<p class='error_message'>Zadané přihlašovácí údaje neodpovídají.</p>";
                    }
                    else if($_GET["error"] == "emptyinput"){
                        echo "<p class='error_message'>Vyplňte všechny pole.</p>";
                    }
                    else if($_GET["error"] == "nologincalendar"){
                        echo "<p class='error_message'>Pro přístup do kalendáře se prosím přihlašte nebo zaregistrujte.</p>";
                        
                    }
                }

            ?>
            </form>
            

        </div>

      
               
    
    
        
        
