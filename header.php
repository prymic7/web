<?php
    include_once 'includes/functions.inc.php';
    include_once 'includes/dbh.inc.php';

    session_start();
    $id_logged = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
    $logged_username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
    $user_logged = isset($_SESSION["username"]) ? true : false;

    if(!$user_logged)
    {
        // header("location:loginpage.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style-profile.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@500&family=Oswald&display=swap" rel="stylesheet">
    

    
    

    <title>Document</title>
</head>
<body>
    <div class="friend-req-action">
        <p id="friend-req-action-text">Ahoj jak se mas ja jsem vocas</p>
        <img src="img/checkedicon.png" alt="" id="checked-icon">
    </div>


    <div class="overlay"></div>
    <img src="img/katkalogo1.png" alt="" id=jedna>
    <header>
        <nav>
            
            <ul class="levoul">
                <li id="homenav"><a href="home.php"><img src="img/homenav.png" alt="" id="homenavimg" class="nav-list"></a></li>
                <li id="calendarnav"><a href="calendar.php"><img src="img/calendarnav.png" alt="" id="calendarnavimg" class="nav-list"></a></li>
                <li id="globenav"><a href="games.php" style="width:30px; height:30px; display:flex"></a><img src="img/gamesicon.png" alt="" id="globenavimg" class="nav-list" id="games-icon"></li>
                <li id="msgnav"><img src="img/msgnav.png" alt="" id="msgnavimg" class="nav-list"></li>
                <li id="addfriendnav"><img src="img/addfriendnav.png" alt="" id="addfriendnavimg" class="nav-list"></li>
                <?php
                    if($user_logged){
                        echo "<div class='search-for-user-all'>";
                            echo "<form action='usersearchresult.php' method='post'>";
                            echo "<input type='text' class='search-for-user-bar' name='textuser' placeholder='Koho hledáte?..' class='input-bar1'>";
                            echo "<input type='submit' class='submitbutton' value='Hledat' name='submituser'>";
                            echo "</form>";
                        echo "</div>";
                    }
                ?>

                <div class="list-of-req">
                    <img src="img/whatsup1.png" alt="" id="whatsup-img">
                    <div class="every-request">
                        <ul class="unordered-list-req"></ul>
                            
                    </div>
                    <div class="no-requests">
                        <img src="img/iconcryingface.png" alt="" id="crying-face-img">
                        <p id="no-requests-text">Nemáte žádné žádosti o přátelství.</p>
                        
                    </div>
                    

            
                </div>
            </ul>

            <ul class="pravoul">
                <?php
                    
                    if($user_logged){
                        echo "<div class='logged-right-nav'>";
                            echo "<li id='list-profile'><a class='pravoul-text' href='profile.php?id={$_SESSION['id']}'>{$_SESSION['username']}</a></li>";
                            echo "<li id='list-logout'><a href='includes/logout.inc.php'><img src='img/iconlogout.png' alt='' id='icon-logout'></a></li>";
                        echo "</div>";
                    } else {
                        echo "<div class='unlogged-right-nav'>";
                            echo "<li id='list-login'><a class='pravoul-text' href='loginpage.php' class='jojo'>Přihlásit</a></li>";
                            echo "<li id='list-register'><a class='pravoul-text' href='registerpage.php'>Registrovat</a></li>" ; 
                        echo "</div>";
                    }

                ?>
                
            </ul>
        </nav>
    </header>
    
    <script>

        // document.querySelector("#globenavimg").addEventListener("click", function(){
        //     window.link = "games.php";
        // })
        function getSearchedUser(data){
            let removedDiv = document.querySelector(".returned-users")
            if(removedDiv){
                removedDiv.remove()
            }
            
            $.ajax({
                url: 'includes/searchuser.inc.php',
                data: {search: data},
                method: 'POST',
                success: function(response) {
                    response = JSON.parse(response)
                    if(response.length > 0){
                        let newDivUsers = document.createElement("div")
                        newDivUsers.className = "returned-users"
                        
                        console.log(response)
        
                        for(let i = 0; i < response.length; i++){
                            let newDivUser = document.createElement("div")
                            newDivUser.className = "returned-user"
                            let userName = document.createElement("a")
                            userName.setAttribute("href", "profile.php?id=" + response[i]["id"])
                            userName.textContent = response[i]["username"]
                            userName.className = "user-search-letter"
                            newDivUser.appendChild(userName)
                            newDivUsers.appendChild(newDivUser)
                        }
                        document.body.appendChild(newDivUsers)
                        console.log(newDivUsers)
                    }
                    
                
                },
                error: function(xhr, status, error) {
                
                console.error(error);
                console.error(xhr);
                console.error(status);
                }
            });
        }
        let searchBar = document.querySelector(".search-for-user-bar");
        searchBar.addEventListener('input', function(){
            getSearchedUser(searchBar.value)
        });


        let calendarnavimg = document.querySelector("#calendarnav")
        calendarnavimg.addEventListener("click", function(){
            console.log("jojo");
        })

        let globenavimg = document.querySelector("#globenavimg")
        globenavimg.addEventListener("click", function(){
            window.location.href = "games.php"
        })

        let reqNumDiv = document.querySelector("#addfriendnav")
        let listReqDiv = document.querySelector(".list-of-req")
        reqNumDiv.addEventListener("click", function(){
            if(listReqDiv.style.display === "block"){
                listReqDiv.style.display = "none";
                console.log("suii");
            } else {
                console.log("suii2");
                listReqDiv.style.display = "block";
            }
            
        })
        let addImg = document.querySelector("#add-friend-id");
        let removeImg = document.querySelector("#remove-friend-id");
        
        removeImg.addEventListener("click", function(){
            console.log("kokotkonemozezato");
            $.ajax({
                url: 'includes/changereqstatus.inc.php',
                method: 'POST',
                success: function(response) {
                    console.log(response)
                },
                error: function(xhr, status, error) {
                
                console.error(error);
                console.error(xhr);
                console.error(status);
                }
            });
        })
        
        
        

    </script>
    
    <?php
        
        if(isset($_SESSION["username"])){
            if($friend_requests = loadFriendRequests($id_logged, $connection)){
                
                
                
                foreach ($friend_requests as $index => $request) {
                    $status = $request["statusak"];
                    
                    
                    if($status === "pending"){
                        
                        $sender_id = $request["sender_id"];
                        $receiver_id = $request["receiver_id"];
                        $userinfo = loadUserInfo($sender_id, $connection);
                        $username = $userinfo["username"];
                    
                        echo '<script> 
                            document.addEventListener("DOMContentLoaded", function() {
                                let createdDiv = document.createElement("div");
                                createdDiv.className = "one-slab";
                                let bigDiv = document.querySelector(".every-request")
                                let ul = document.querySelector(".unordered-list-req");
                                let list = document.createElement("li");
                                list.className = "request-list";

                                list.id = ' . $index . '
                                list.textContent = "' . $username . ' chce být přítel.";
                                

                                let imgDiv = document.createElement("div");
                                imgDiv.className = "request-img-div";
                                let addImg = document.createElement("img");
                                let removeImg = document.createElement("img");
                                addImg.className = "add-friend-class";
                                removeImg.className = "remove-friend-class";
                                addImg.id = ' . $index . '
                                removeImg.id = ' . $index . '
                                addImg.src = "img/addfriend.png";
                                removeImg.src = "img/removefriend.png";
                                imgDiv.appendChild(addImg);
                                imgDiv.appendChild(removeImg);
                                createdDiv.append(imgDiv);

                                
                                removeImg.addEventListener("click", function(){
                                    let imgid = removeImg.id;
                                    let listRightId = document.querySelector(".request-list");
                                    
                                    let receiver_id = ' .$receiver_id. ';
                                    let sender_id = ' .$sender_id. ';
                                    let action = "reject";
                                    
                                    $.ajax({
                                        url: "includes/changereqstatus.inc.php",
                                        method: "POST",
                                        data: {sender: sender_id, receiver: receiver_id, action: action},
                                        success: function(response) {
                                            let slab = document.querySelector(".one-slab");
                                            slab.remove();

                                        },
                                        error: function(xhr, status, error) {
                                        
                                        console.error(error);
                                        console.error(xhr);
                                        console.error(status);
                                        }
                                    });
                                })

                                addImg.addEventListener("click", function(){
                                    let imgid = addImg.id;
                                    let listRightId = document.querySelector(".request-list"); 

                                    let receiver_id = ' .$receiver_id. ';
                                    let sender_id = ' .$sender_id. ';
                                    let action = "accept";

                                    $.ajax({
                                        url: "includes/changereqstatus.inc.php",
                                        method: "POST",
                                        data: {sender: sender_id, receiver: receiver_id, action: action},
                                        success: function(response) {
                                            let slab = document.querySelector(".one-slab");
                                            slab.remove();
                                            
                                            let divAction = document.querySelector(".friend-req-action");
                                            let textAction = document.querySelector("#friend-req-action-text");
                                            textAction.textContent = " Vy a '. $username .' jste se stali přáteli. "
                                            
                                            divAction.style.display = "flex";
                                            divAction.classList.add("slowly-appear");

                                            
                                            setTimeout(function() {
                                                divAction.classList.remove("slowly-appear");
                                                divAction.classList.add("slowly-hide");
                                            }, 3000);
                                         
                                            
                                        },
                                        error: function(xhr, status, error) {
                                        
                                        console.error(error);
                                        console.error(xhr);
                                        console.error(status);
                                        }
                                    });
                                })


                                createdDiv.append(list);
                                ul.appendChild(createdDiv);
                            });
                        </script>';
                    }
                }

                $request_count = count($friend_requests);
                    $count = 0;
                    foreach($friend_requests as $index => $request){
                        if($request["statusak"] === "pending"){
                            $count++;
                        }
                    }

                echo '<script> document.addEventListener("DOMContentLoaded", function(){
                    let levoul = document.querySelector(".levoul");
                    // let globeNumDiv = document.createElement("div");
                    // let globeNumText = document.createElement("p");

                    // let msgNumDiv = document.createElement("div");
                    // let msgNumText = document.createElement("p");

                    // msgNumText.id = "msg-num-text";
                    // msgNumDiv.className = "msg-num-div";

                    // globeNumText.id = "globe-num-text";
                    // globeNumDiv.className = "globe-num-div";

                    if("'.$count.'" !== "0"){
                        console.log("' . $count .'")
                        let reqNumDiv = document.createElement("div");
                        let reqNumText = document.createElement("p");

                        reqNumText.id = "req-num-text";
                        reqNumDiv.className = "req-num-div";

                        reqNumText.textContent = ' . $count . ' ;

                        
                        reqNumDiv.appendChild(reqNumText);

                        levoul.appendChild(reqNumDiv)
                        noRequestsDiv = document.querySelector(".no-requests");
                        noRequestsDiv.style.display = "none";
                    } else {
                        console.log("je nula")
                        if(document.querySelector(".req-num-div")){
                            document.querySelector(".req-num-div").remove();
                        }
                        
                    }
                    
                    



                    
                }); </script>'; 
            }
            
        }

    ?>
    <div class="wrapper clearfix">


    