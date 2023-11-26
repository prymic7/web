<?php
    include_once 'header.php';
    include_once 'includes/dbh.inc.php';
    include_once 'includes/functions.inc.php';


    if(!isset($_SESSION["username"])){
        header("location: loginpage.php");
    }
?>
<?php



    if(isset($_POST["textuser"])){
        $user_search = $_POST["textuser"];
        $sessionID = $_SESSION["id"];
        
        
        $sql = "SELECT * FROM userinfo WHERE username LIKE ?";
        $stmt = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../registerpage.php?error=stmtfailed");
            exit();
        }
        $user_search = $user_search . "%"; 
        mysqli_stmt_bind_param($stmt, "s", $user_search);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        $rows = mysqli_fetch_all($resultData, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        
        $count = 0;
        $user_search = str_replace('%', '', $user_search);
        $dateNow = date("Y-m-d");
        //list($yearNow, $monthNow, $dayNow) = explode("-", $dateNow);
        
        foreach($rows as $index => $row){
            
            $id = $row["id"];
            $date = $row["date"];
            $profileimage;
            if(!empty($row["profileimage"])){
                $profileimage = $row["profileimage"];
            }
            else {
                $profileimage = "/9j/4AAQSkZJRgABAQEBLAEsAAD/4QBWRXhpZgAATU0AKgAAAAgABAEaAAUAAAABAAAAPgEbAAUAAAABAAAARgEoAAMAAAABAAIAAAITAAMAAAABAAEAAAAAAAAAAAEsAAAAAQAAASwAAAAB/+0ALFBob3Rvc2hvcCAzLjAAOEJJTQQEAAAAAAAPHAFaAAMbJUccAQAAAgAEAP/hDIFodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvADw/eHBhY2tldCBiZWdpbj0n77u/JyBpZD0nVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkJz8+Cjx4OnhtcG1ldGEgeG1sbnM6eD0nYWRvYmU6bnM6bWV0YS8nIHg6eG1wdGs9J0ltYWdlOjpFeGlmVG9vbCAxMC4xMCc+CjxyZGY6UkRGIHhtbG5zOnJkZj0naHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyc+CgogPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9JycKICB4bWxuczp0aWZmPSdodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyc+CiAgPHRpZmY6UmVzb2x1dGlvblVuaXQ+MjwvdGlmZjpSZXNvbHV0aW9uVW5pdD4KICA8dGlmZjpYUmVzb2x1dGlvbj4zMDAvMTwvdGlmZjpYUmVzb2x1dGlvbj4KICA8dGlmZjpZUmVzb2x1dGlvbj4zMDAvMTwvdGlmZjpZUmVzb2x1dGlvbj4KIDwvcmRmOkRlc2NyaXB0aW9uPgoKIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PScnCiAgeG1sbnM6eG1wTU09J2h0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8nPgogIDx4bXBNTTpEb2N1bWVudElEPmFkb2JlOmRvY2lkOnN0b2NrOjlmOGM2NzA4LTZjZGItNDE3My1hZmFhLTg2MTMxYjQyYjEyZjwveG1wTU06RG9jdW1lbnRJRD4KICA8eG1wTU06SW5zdGFuY2VJRD54bXAuaWlkOjkzNTNjMzAzLWFhMTktNGY2Yi04MDQwLTFkOTc0OWM1YzU5YjwveG1wTU06SW5zdGFuY2VJRD4KIDwvcmRmOkRlc2NyaXB0aW9uPgo8L3JkZjpSREY+CjwveDp4bXBtZXRhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAo8P3hwYWNrZXQgZW5kPSd3Jz8+/9sAQwAFAwQEBAMFBAQEBQUFBgcMCAcHBwcPCwsJDBEPEhIRDxERExYcFxMUGhURERghGBodHR8fHxMXIiQiHiQcHh8e/9sAQwEFBQUHBgcOCAgOHhQRFB4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4e/8AAEQgBaAFoAwERAAIRAQMRAf/EABsAAQADAQEBAQAAAAAAAAAAAAAEBQYDAgEI/8QAOhABAAIBAgMDCQcCBgMAAAAAAAECAwQRBSExEkFRBhNSYWJxkaGxFCIjMoHB0RU0M3JzgpLhJGPw/8QAFgEBAQEAAAAAAAAAAAAAAAAAAAEC/8QAFhEBAQEAAAAAAAAAAAAAAAAAAAER/9oADAMBAAIRAxEAPwD9LNMgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAPGbLjw17WXJWkeuQQc3F9NTljrfJ8oMEa/Gcs/kwUj3zMrg8f1jVeji/4z/Jg+14zqI64sU/GDB1pxr09P8A8bJgk4uK6S/K1rY59qP4MEzHkx5Y3x5K3j2Z3B6AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABy1Gpwaeu+bJFfCO+f0BV6zi97b101exHpW6/BcFZe98lpte02tPfM7g8gAAAAARvE7xO0+MAveH8S09sdMV98Vojb707xP6/ygsQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAVvFOIxhmcOCYnJ326xX/swUl7WvabXtNrT1mZ5qPgAAAAAAAAAJvD+IZdNMUtvfF6PfHuBf4slMuOuTHaLVt0lB6AAAAAAAAAAAAAAAAAAAAAAAAAAAAABU8Z11q2nTYbbTH57R9FgpwAAAAAAAAAAAAWHBdVOHURhtP4eSdvdPcC+QAAAAAAAAAAAAAAAAAAAAAAAAAAAAec14x4r5J6VrMgylrTe02tO8zO8qPgAAAAAAAAAAAAG8xzjrANZit28VL+lWJ+SD0AAAAAAAAAAAAAAAAAAAAAAAAAAACv47nnFpYxVn72Wdp93eQUKgAAAAAAAAAAAAADRcFyec4fTfrSZqlEwAAAAAAAAAAAAAAAAAAAAAAAAAAAFBx3J2td2e6lYj91ggAAAAAAAAAAAAAAAu/J2Z+zZY8L/ALJRZgAAAAAAAAAAAAAAAAAAAAAAAAAAAzPE7driGefbmFEcAAAAAAAAAAAAAAF35O2j7Plr3xeJ+SUWYAAAAAAAAAAAAAAAAAAAAAAAAAAAMvrv73P/AKk/VRxAAAAAAAAAAAAAABaeTtvxs1fGsT8/+yi6QAAAAAAAAAAAAAAAAAAAAAAAAAAAZjiEba7PH/slRwAAAAAAAAAAAAAABY+T/wDe3/05+sFF6gAAAAAAAAAAAAAAAAAAAAAAAAAAAzfF69niOb1zE/JRFAAAAAAAAAAAAAABZeT0f+Vknwx/uUXiAAAAAAAAAAAAAAAAAAAAAAAAAAD5kvTHSb3tFa1jeZkGd4rmxZ9X53DaZrNYid425qIgAAAAAAAAAAAAAALHgmowae2Sc1+zN9ojlyKL1AAAAAAAAAAAAAAAAAAAAAAAAAABD43v/Tr7eNfqQZ1QAAAAAAAAAAAAAAABqtJv9kw79exX6IOgAAAAAAAAAAAAAAAAAAAAAAAAAI3FY34dm9Vd/mQZpQAAAAAAAAAAAAAAABraR2aVr4REIPoAAAAAAAAAAAAAAAAAAAAAAAAAOWrp5zS5ccdbUmIBllAAAAAAAAAAAAAAAHvBScmfHjjra0R8waxB8AAAAAAAAAAAAAAAAAAAAAAAAAABltXTzWqy4+6t5iFHIAAAAAAAAAAAAAAE3glO3xCkz0rE2/8AviDQoAAAAAAAAAAAAAAAAAAAAAAAAAAAKHjWmyV1ds0Um1L894jfaVgr+nUAAAAAAAAAAAAACImekTPuBccB02Slr58lZrE17NYnrPrSi2AAAAAAAAAAAAAAAAAAAAAAAAAAAABneM07PEcntbW+SiGAAAAAAAAAAAAC28nafiZsvhEVj6lFwgAAAAAAAAAAAAAAAAAAAAAAAAAAAAApvKLHtkxZo74msrBVAAAAAAAAAAAAA0PBMXm9BW09ckzb+EomgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAjcUwTqNFetY3tX71ffBBmlAAAAAAAAAAAHvDjtmzUxV62naAaqlYpStK9KxtCD6AAAAAAAAAAAAAAAAAAAAAAAAAAAAAACi4zo/MZPP44/DvPOPRlRXAAAAAAAAAAAueA6Xav2q8c53inu75Si1AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABx19IyaLNSfQmf1jmDLqAAAAAAAAAEg1OjrFdJhrHdSPolHUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHjUf2+X/JP0BlI6KAAAAAAAAAE9AavT/2+L/JH0QewAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAcOIX7GhzW9iY+PIgzCgAAAAAAAAADT8Pv29Dht7ER8OSDuAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADjqdVg00fi5Iie6sc5n9DBTcR4lbU45xUp2Me+/OecqIAAAAAAAAAAAJ/DuJW02OMV6dvHvvG084Bc6bVYNTH4WSJnvrPKY/RMHYAAAAAAAAAAAAAAAAAAAAAAAAAAAAFfxvU5tPjx1xT2e3vvbvj3EFFMzMzMzMzPWZUfAAAAAAAAAAAAAfYmYmJiZiY6TAL3gmpzajHkrlntdjba3fPvSiwAAAAAAAAAAAAAAAAAAAAAAAAAABE1nEdPp969rzmT0a/vJgpNbrM2rtE5JiKx+WsdIURwAAAAAAAAAAAAAASNFrM2ktM45iaz+as9JBd6PiOn1G1e15vJ6Nv2lMEsAAAAAAAAAAAAAAAAAAAAAACeUbzygELVcT02HeK287bwr0+JgqtXxHU6jeva83T0a/vKiGAAAAAAAAAAAAAAAAAACZpOI6nT7V7XnKejb9pBa6XiemzbRa3mreFunxTBNjnG8c4AAAAAAAAAAAAAAAAAABHz63S4fz5q7+Fec/IwQNRxnuwYv91/4XBXajVajUT+LltaPDpHwBxAAAAAAAAAAAAAAAAAAAAAAB20+q1Gnn8LLaseHWPgCx0/Ge7Pi/3U/gwT8Gt0ub8mau/hblPzTBIAAAAAAAAAAAAnlG88oBB1PFNNima03y29np8TBX5uLaq/5Ipjj1RvPzXBEy6jPl/wATNe3qmeQOQAAAAAAAAAAAAAAAAAAAAAAAAAAAAOuLUZ8X+HmvX1RPIEvDxbVU/PFMkeuNp+RgsNNxTTZZit98Vva6fFME6OcbxzgAAAAAAAHLVajFpsXnMs8u6I6zPqBQa3XZtVba09nH3Ujp+vioigAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAlaLXZtLbas9rH30np+ngC/0uoxanF5zFPLviesT60HUAAAAHnLkrixWyXnatY3mQZrW6m+qzzkvyjpWvhCjgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADvotTfS54yU5x0tXxgGlxZK5cVclJ3raN4lB6AAABU+UGeYimniev3rfsQU6gAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAC48n88zF9PM9PvV/dKLYAAAGd4zbtcRyeztX5LBDAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABM4Nbs8Rx+1vX5FGiQAf/9k=";

            }

            $secondsDiff = strtotime($dateNow) - strtotime($date);
            $daysDiff = floor($secondsDiff / (60 * 60 * 24));
            
            $username = $row["username"];
            $relation = bringStatus($id, $id_logged, $connection);
            
            
            if(isFriend($id_logged, $id, $connection) === "accepted"){
                $areFriends = 1;
            } 
            if(isFriend($id_logged, $id, $connection) === "pending"){
                $areFriends = 2;
            } 
            if(isFriend($id_logged, $id, $connection) === "rejected"){
                $areFriends = 3;
            } 
            if(!isFriend($id_logged, $id, $connection)){
                $areFriends = 0;
            }
            
            
            
            

            






            
           
            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
            let containerDiv = document.querySelector(".results-container");

            let userMainDiv = document.createElement("div");
            let userImgDiv = document.createElement("div");
            let userNameDiv = document.createElement("div");
            let userDateDiv = document.createElement("div");
            let userActionDiv = document.createElement("div");
            let profilRedirectDiv = document.createElement("div");
            let profilText = document.createElement("p");
            
            profilText.textContent = "profil";
            profilRedirectDiv.append(profilText);
            
            userMainDiv.className = "search-results-main-div";
            userImgDiv.className = "search-result-img-div";
            userNameDiv.className = "search-result-name-div";
            userDateDiv.className = "search-result-date-div";
            userActionDiv.className = "search-result-action-div";
            profilRedirectDiv.className = "search-result-profile-redirect";

            profilRedirectDiv.addEventListener("click", function(){
                window.location.href = "profile.php?id=' . $id . '";
            })

            userNameDiv.addEventListener("click", function(){
                window.location.href = "profile.php?id=' . $id . '";
                userNameDiv.style.cursor = "pointer !important";
                
            })
            

            if(' .$index. ' === 0){
                userMainDiv.style.borderTop = "2px solid #E5E4E2";
                userMainDiv.style.borderRadius = "10px 10px 0px 0px";
            }

            if(' .count($rows) - 1 . ' === ' .$index. '){
                userMainDiv.style.borderBottom = "2px solid #E5E4E2";
                userMainDiv.style.borderRadius = "0px 0px 10px 10px";
            }

            userMainDiv.appendChild(userImgDiv);
            userMainDiv.appendChild(userNameDiv);
            userMainDiv.appendChild(userDateDiv);
            userMainDiv.appendChild(userActionDiv);
            userMainDiv.appendChild(profilRedirectDiv);

            let userNameText = document.createElement("p");
            let userDateText = document.createElement("p");
            let userImg = document.createElement("img");
            let actionImg = document.createElement("img");
            actionImg.id = "icon-action-img";
            

            

            
            if(' .$areFriends . ' === 0){
                console.log("1")
                actionImg.addEventListener("click", function(){
                    $.ajax({
                        url: "includes/sendfriendrequest.inc.php",
                        method: "POST",
                        data: {id_receiver: '.$id.', id_sender: '.$id_logged.'},
                        success: function(response) {
                            
                        },
                        error: function(xhr, status, error) {
                            
                            console.error(error);
                            console.error(xhr);
                            console.error(status);
                        }
                        });
                })
                actionImg.src = "img/iconaddfriend.png";

                

            } else if(' .$areFriends . ' === 1) {
                console.log("2")
                actionImg.src = "img/iconfriendschecked.png";
                
            } else if(' .$areFriends . ' === 2){
                console.log("3")
                actionImg.src = "img/iconwaiting.png";
            } else if(' .$areFriends . ' === 3){
                console.log("3")
                actionImg.src = "img/iconremovefriend.png";
                actionImg.style.width = "40px";
                actionImg.style.height = "40px";
                // actionImg.style.marginLeft = "5px";
                profilRedirectDiv.style.marginLeft = "25px";
            }


            userNameText.textContent = "' .$username. '";
            userDateText.textContent = "Uživatelem '. $daysDiff .' dní.";';
            if(!empty($profileimage)){
                echo 'userImg.src = "data:image/png;base64, ' . $profileimage . '";
                    userImg.style.width = "35px";
                    userImg.style.height = "35px";
                ';

            }

            if(empty($profileimage)){
                echo 'userImg.src = "data:image/png;base64,' .$profileimage. '";
                userImg.style.width = "35px";
                userImg.style.height = "35px";

                ';
            }
        echo '
            userNameDiv.appendChild(userNameText);
            userDateDiv.appendChild(userDateText);
            userImgDiv.appendChild(userImg);
            userActionDiv.appendChild(actionImg);

            containerDiv.appendChild(userMainDiv);

            let paragraph = document.querySelector("#result-text-paragraph")
            
            paragraph.textContent = "Výsledek hledání pro: ' . "$user_search" . '"
                
            });    
        </script>';
            
            


        }

    }
?>

    <div class="result-text">
        <p id="result-text-paragraph"></p>
    </div>
    <div class="search-user-results">
        <div class="results-container">

        </div>
    </div>







<?php
    include_once 'footer.php';
?>