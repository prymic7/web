<?php
    include_once 'header.php';
    include_once 'includes/functions.inc.php';
    include_once 'includes/dbh.inc.php';

    $name;
    $id;
    $id = $_GET['id'];
    if(!$user_logged)
    {
        header("location:loginpage.php");
    }
    $userinfo = loadUserInfo($id, $connection);
    $username = $userinfo["username"];



 


    
    //Error handling
    if(isset($_GET["error"])){
        $errorMessage = $_GET["error"];
        echo '<script>document.addEventListener("DOMContentLoaded", function(){
            let newDiv = document.createElement("div");
            newDiv.className = "error-popup";
            let profile = document.querySelector(".profile");
            profile.append(newDiv); 
            let msg = document.createElement("p");
            msg.className = "error_message_img_insert"; 
            let exit_btn = document.createElement("img");
            exit_btn.id = "exit_btn";
            exit_btn.src = "img/exiticon.png";
            newDiv.appendChild(exit_btn);
    
            if("' . $errorMessage . '" == "largesize"){
                msg.textContent = "Velikost obrázku je příliš velká. Zvolte jiný obrázek.";
            }
            else if("' . $errorMessage . '" == "wrongfiletype"){
                msg.textContent = "Zvolte zvolte jiný typ obrázku(.jpg, .jpeg, .png).";
            }
            else if("' . $errorMessage . '" == "somethingwentwrong"){
                msg.textContent = "Něco se pokazilo.";
            }
            newDiv.append(msg);
    
            let div = document.querySelector(".error-popup");
            let overlay = document.querySelector(".overlay")
            if(exit_btn) {
                
                exit_btn.addEventListener("click", function(){
                    overlay.style.display = "none";
                    div.remove();
                    
                });
            }

           
            overlay.style.display = "block";

            
            

        }); </script>';
    }
    
    
        
    

    //Nahrani obrazku na stranku
    if(!checkEmptyImage($id, $connection)){
        echo '<script>document.addEventListener("DOMContentLoaded", function(){
            let contDiv = document.querySelector(".profile-picture");
            let image = document.createElement("img");
            image.id = "profile-picture";
            image.src = "img/nophotoimg.jpg";
            contDiv.appendChild(image);
        }); </script>';
    }

    if ($userphoto = checkEmptyImage($id, $connection)) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                let contDiv = document.querySelector(".profile-picture");
                let image = document.createElement("img");
                image.id = "profile-picture";
                image.src = "data:image/jpeg;base64,' . $userphoto . '";
                contDiv.appendChild(image);
                
            });
        </script>';
        
    }
    $sender_id = $id_logged;
    $receiver_id = $id;
    if($friend_request = bringStatus($id, $id_logged, $connection)){
        $status = $friend_request["statusak"];
        
        echo '<script> 
            console.log("'.$status.'");
            document.addEventListener("DOMContentLoaded", function() {
                if("' . $status . '" === "accepted") {
                    let text = document.createElement("p");
                    let imgch = document.createElement("img");
                    let imgi = document.createElement("img");
                    let div = document.querySelector(".add-friend");
                    imgch.src = "img/checkedicon.png";
                    imgch.id = "icon-add-friend-profile";
                    imgi.src = "img/iconarrowup.png";
                    imgi.id = "icon-arrow-down";
                    text.id = "text-add-friend";
                    text.textContent = "Přátelé";
                    div.appendChild(imgch)
                    div.appendChild(text)
                    div.appendChild(imgi)

                    imgch.style.width = "15px";
                    imgch.style.height = "15px";
                    imgi.style.width = "10px";
                    imgi.style.height = "10px";
                    imgch.style.marginRight = "10px";

                    let wholeDiv = document.querySelector(".add-friend");
                    wholeDiv.addEventListener("click", function() {
                        if(!document.querySelector(".delete-friend-div")){
                            let timelineDiv = document.querySelector(".timeline");
                            let deleteFriendDiv = document.createElement("div");
                            let deleteFriendText = document.createElement("p");
                            let deleteFriendImg = document.createElement("img");
                            deleteFriendText.id = "delete-friend-text";
                            deleteFriendImg.id = "delete-friend-img";
                            deleteFriendImg.src = "img/iconremovefriend.png";
                            deleteFriendDiv.className = "delete-friend-div";
                            deleteFriendDiv.id = "opened";
                            deleteFriendText.textContent = "Odebrat přítele";
                            deleteFriendDiv.append(deleteFriendImg);
                            deleteFriendDiv.append(deleteFriendText);
                            timelineDiv.appendChild(deleteFriendDiv);
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowdown.png";

                            document.querySelector(".delete-friend-div").addEventListener("click", function(){
            
                                let username = "<?php echo $username ?>"
                                
                                let action = "reject"
                                $.ajax({
                                    url: "includes/changereqstatus.inc.php",
                                    method: "POST",
                                    data: {sender: ' .$sender_id. ', receiver: ' .$receiver_id. ', action: action},
                                    success: function(response) {
                                        console.log(response);
                                        let divAction = document.querySelector(".friend-req-action");
                                        let textAction = document.querySelector("#friend-req-action-text");
                                        textAction.textContent = "Vy a ' . $username . ' už nejste přáteli."
                                        
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
                                
                            });
                            
                        } else if(document.querySelector(".delete-friend-div") && document.querySelector(".delete-friend-div").id === "opened") {
                            document.querySelector(".delete-friend-div").style.display = "none";
                            document.querySelector(".delete-friend-div").id = "closed";
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowup.png";
                            

                        } else if(document.querySelector(".delete-friend-div") && document.querySelector(".delete-friend-div").id === "closed") {
                            document.querySelector(".delete-friend-div").style.display = "flex";
                            document.querySelector(".delete-friend-div").id = "opened";
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowdown.png";
                        }
                        

                    })
                    
                    
                }

                else if("' . $status . '" === "pending") {

                    let text = document.createElement("p");
                    let imgi = document.createElement("img");
                    let imgch = document.createElement("img");
                    let div = document.querySelector(".add-friend");
                    imgch.src = "img/iconwaiting.png";
                    imgi.src = "img/iconarrowup.png"; 
                    imgch.id = "icon-add-friend-profile";
                    imgi.id = "icon-arrow-down";
                    text.id = "text-add-friend";
                    text.textContent = "Žádost odeslána";
                    div.appendChild(imgi)
                    div.appendChild(imgch)
                    div.appendChild(text)

                    imgch.style.width = "15px";
                    imgch.style.height = "15px";
                    imgch.marginRight = "5px";
                    imgi.style.width = "15px";
                    imgi.style.height = "15px";
                    text.style.marginRight = "15px";
                    text.style.fontSize = "13px";
                    imgi.style.marginLeft = "110px";

                    let wholeDiv = document.querySelector(".add-friend");
                    wholeDiv.addEventListener("click", function() {
                        if(!document.querySelector(".delete-friend-div")){
                            let timelineDiv = document.querySelector(".timeline");
                            let deleteFriendDiv = document.createElement("div");
                            let deleteFriendText = document.createElement("p");
                            let deleteFriendImg = document.createElement("img");
                            deleteFriendText.id = "delete-friend-text";
                            deleteFriendImg.id = "delete-friend-img";
                            deleteFriendImg.src = "img/iconremovefriend.png";
                            deleteFriendDiv.className = "delete-friend-div";
                            deleteFriendDiv.id = "opened";
                            deleteFriendText.textContent = "Zřušit žádost.";
                            deleteFriendDiv.append(deleteFriendImg);
                            deleteFriendDiv.append(deleteFriendText);
                            timelineDiv.appendChild(deleteFriendDiv);
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowdown.png";
                            document.querySelector(".delete-friend-div").style.marginTop = "-240px";
                            document.querySelector(".delete-friend-div").addEventListener("click", function(){
            
                                let username = "<?php echo $username ?>"
                                
                                let action = "reject"
                                $.ajax({
                                    url: "includes/changereqstatus.inc.php",
                                    method: "POST",
                                    data: {sender: ' .$sender_id. ', receiver: ' .$receiver_id. ', action: action},
                                    success: function(response) {
                                        console.log(response);
                                        let divAction = document.querySelector(".friend-req-action");
                                        let textAction = document.querySelector("#friend-req-action-text");
                                        textAction.textContent = "Vy a ' . $username . ' už nejste přáteli."
                                        
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
                                
                            });
                            
                        } else if(document.querySelector(".delete-friend-div") && document.querySelector(".delete-friend-div").id === "opened") {
                            document.querySelector(".delete-friend-div").style.display = "none";
                            document.querySelector(".delete-friend-div").id = "closed";
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowup.png";
                            

                        } else if(document.querySelector(".delete-friend-div") && document.querySelector(".delete-friend-div").id === "closed") {
                            document.querySelector(".delete-friend-div").style.display = "flex";
                            document.querySelector(".delete-friend-div").id = "opened";
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowdown.png";
                        }
                        

                    })
                    
                    
                
                }

                else if("' . $status . '" === "rejected") {
                    let text = document.createElement("p");
                    let img = document.createElement("img");
                    let div = document.querySelector(".add-friend");
                    img.src = "img/removefriend.png";
                    img.id = "icon-add-friend-profile";
                    text.id = "text-add-friend";
                    text.textContent = "Žádost zamítnuta";
                    div.appendChild(img)
                    div.appendChild(text)

                    img.style.width = "15px";
                    img.style.height = "15px";
                    text.style.marginLeft = "0px";
                    text.style.fontSize = "13px";
                } 

            });
            
        </script>';
    } else if ($friend_request = bringStatus($id_logged, $id, $connection)){
        $status = $friend_request["statusak"];
        echo '<script> 
            console.log("'.$status.'");
            document.addEventListener("DOMContentLoaded", function() {
                if("' . $status . '" === "accepted") {
                    console.log("accepted")
                    let text = document.createElement("p");
                    let imgch = document.createElement("img");
                    let imgi = document.createElement("img");
                    let div = document.querySelector(".add-friend");
                    imgch.src = "img/checkedicon.png";
                    imgch.id = "icon-add-friend-profile";
                    imgi.src = "img/iconarrowup.png";
                    imgi.id = "icon-arrow-down";
                    text.id = "text-add-friend";
                    text.textContent = "Přátelé";
                    div.appendChild(imgch)
                    div.appendChild(text)
                    div.appendChild(imgi)

                    imgch.style.width = "15px";
                    imgch.style.height = "15px";
                    imgi.style.width = "10px";
                    imgi.style.height = "10px";
                    imgch.style.marginRight = "10   px";

                    let wholeDiv = document.querySelector(".add-friend");
                    wholeDiv.addEventListener("click", function() {
                        if(!document.querySelector(".delete-friend-div")){
                            let timelineDiv = document.querySelector(".timeline");
                            let deleteFriendDiv = document.createElement("div");
                            let deleteFriendText = document.createElement("p");
                            let deleteFriendImg = document.createElement("img");
                            deleteFriendText.id = "delete-friend-text";
                            deleteFriendImg.id = "delete-friend-img";
                            deleteFriendImg.src = "img/iconremovefriend.png";
                            deleteFriendDiv.className = "delete-friend-div";
                            deleteFriendDiv.id = "opened";
                            deleteFriendText.textContent = "Odebrat přítele";
                            deleteFriendDiv.append(deleteFriendImg);
                            deleteFriendDiv.append(deleteFriendText);
                            timelineDiv.appendChild(deleteFriendDiv);
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowdown.png";
                            document.querySelector(".delete-friend-div").style.marginTop = "-240px";
                            document.querySelector(".delete-friend-div").addEventListener("click", function(){
            
                                let username = "<?php echo $username ?>"
                                
                                
                                $.ajax({
                                    url: "includes/changereqstatus.inc.php",
                                    method: "POST",
                                    data: {sender: ' .$receiver_id. ', receiver: ' .$sender_id. ', action: "reject"},
                                    success: function(response) {
                                        console.log(response);
                                        let divAction = document.querySelector(".friend-req-action");
                                        let textAction = document.querySelector("#friend-req-action-text");
                                        textAction.textContent = "Vy a ' . $username . ' už nejste přáteli."
                                        
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
                                
                            });
                            
                        } else if(document.querySelector(".delete-friend-div") && document.querySelector(".delete-friend-div").id === "opened") {
                            document.querySelector(".delete-friend-div").style.display = "none";
                            document.querySelector(".delete-friend-div").id = "closed";
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowup.png";
                            

                        } else if(document.querySelector(".delete-friend-div") && document.querySelector(".delete-friend-div").id === "closed") {
                            document.querySelector(".delete-friend-div").style.display = "flex";
                            document.querySelector(".delete-friend-div").id = "opened";
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowdown.png";
                        }
                        

                    })
                }  
                else if("' . $status . '" === "pending") {
                    console.log("pending")
                    let text = document.createElement("p");
                    let imgi = document.createElement("img");
                    let imgch = document.createElement("img");
                    let div = document.querySelector(".add-friend");
                    imgch.src = "img/iconwaiting.png";
                    imgi.src = "img/iconarrowup.png"; 
                    imgch.id = "icon-add-friend-profile";
                    imgi.id = "icon-arrow-down";
                    text.id = "text-add-friend";
                    text.textContent = "Odesílá vám žádost";
                    div.appendChild(imgi)
                    div.appendChild(imgch)
                    div.appendChild(text)

                    imgch.style.width = "15px";
                    imgch.style.height = "15px";
                    imgch.marginRight = "5px";
                    imgi.style.width = "10px";
                    imgi.style.height = "10px";
                    text.style.marginRight = "15px";
                    text.style.fontSize = "12px";

                    let wholeDiv = document.querySelector(".add-friend");
                    wholeDiv.addEventListener("click", function() {
                        if(!document.querySelector(".delete-friend-div")){
                            let timelineDiv = document.querySelector(".timeline");
                            let deleteFriendDiv = document.createElement("div");
                            let deleteFriendText = document.createElement("p");
                            let deleteFriendImg = document.createElement("img");
                            deleteFriendText.id = "delete-friend-text";
                            deleteFriendImg.id = "delete-friend-img";
                            deleteFriendImg.src = "img/iconremovefriend.png";
                            deleteFriendDiv.className = "delete-friend-div";
                            deleteFriendDiv.id = "opened";
                            deleteFriendText.textContent = "Zamítnout žádost.";
                            deleteFriendDiv.append(deleteFriendImg);
                            deleteFriendDiv.append(deleteFriendText);
                            timelineDiv.appendChild(deleteFriendDiv);
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowdown.png";

                            document.querySelector(".delete-friend-div").addEventListener("click", function(){
            
                                let username = "<?php echo $username ?>"
                                
                                let action = "reject"
                                $.ajax({
                                    url: "includes/changereqstatus.inc.php",
                                    method: "POST",
                                    data: {sender: ' .$receiver_id. ', receiver: ' .$sender_id. ', action: "reject"},
                                    success: function(response) {
                                        console.log(response);
                                        let divAction = document.querySelector(".friend-req-action");
                                        let textAction = document.querySelector("#friend-req-action-text");
                                        textAction.textContent = "Zamítnuli jste žádost uživateli ' . $username . '."
                                        
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
                                
                            });

                        } else if(document.querySelector(".delete-friend-div") && document.querySelector(".delete-friend-div").id === "opened") {
                            document.querySelector(".delete-friend-div").style.display = "none";
                            document.querySelector(".delete-friend-div").id = "closed";
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowup.png";
                            

                        } else if(document.querySelector(".delete-friend-div") && document.querySelector(".delete-friend-div").id === "closed") {
                            document.querySelector(".delete-friend-div").style.display = "flex";
                            document.querySelector(".delete-friend-div").id = "opened";
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowdown.png";
                        
                        
                        
                        } if(!document.querySelector(".accept-friend-div")){
                            let timelineDiv = document.querySelector(".timeline");
                            let acceptFriendDiv = document.createElement("div");
                            let acceptFriendText = document.createElement("p");
                            let acceptFriendImg = document.createElement("img");
                            acceptFriendText.id = "accept-friend-text";
                            acceptFriendImg.id = "accept-friend-img";
                            acceptFriendImg.src = "img/iconaddfriend.png";
                            acceptFriendDiv.className = "accept-friend-div";
                            acceptFriendDiv.id = "opened";
                            acceptFriendText.textContent = "Potvrdit žádost.";
                            acceptFriendDiv.append(acceptFriendImg);
                            acceptFriendDiv.append(acceptFriendText);
                            timelineDiv.appendChild(acceptFriendDiv);
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowdown.png";

                            document.querySelector(".accept-friend-div").addEventListener("click", function(){
            
                                let username = "<?php echo $username ?>"
                                
                                
                                $.ajax({
                                    url: "includes/changereqstatus.inc.php",
                                    method: "POST",
                                    data: {sender: ' .$receiver_id. ', receiver: ' .$sender_id. ', action: "accept"},
                                    success: function(response) {
                                        console.log(response);
                                        let divAction = document.querySelector(".friend-req-action");
                                        let textAction = document.querySelector("#friend-req-action-text");
                                        textAction.textContent = "Vy a ' . $username . ' jste se stali přáteli."
                                        
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
                                
                            });
                            
                        } else if(document.querySelector(".accept-friend-div") && document.querySelector(".accept-friend-div").id === "opened") {
                            document.querySelector(".accept-friend-div").style.display = "none";
                            document.querySelector(".accept-friend-div").id = "closed";
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowup.png";
                            

                        } else if(document.querySelector(".accept-friend-div") && document.querySelector(".accept-friend-div").id === "closed") {
                            document.querySelector(".accept-friend-div").style.display = "flex";
                            document.querySelector(".accept-friend-div").id = "opened";
                            document.querySelector("#icon-arrow-down").src = "img/iconarrowdown.png";
                        }
                        

                    })
                    
                    
                
                } 
                else if("' . $status . '" === "rejected") {
                    console.log("rejected")
                    
                    let text = document.createElement("p");
                    let img = document.createElement("img");
                    let div = document.querySelector(".add-friend");
                    img.src = "img/removefriend.png";
                    img.id = "icon-add-friend-profile";
                    text.id = "text-add-friend";
                    text.textContent = "Žádost zamítnuta";
                    div.appendChild(img)
                    div.appendChild(text)

                    img.style.width = "15px";
                    img.style.height = "15px";
                    text.style.marginLeft = "0px";
                    text.style.fontSize = "13px";
                } 
                    
            });
        </script>';
    
    } else {
        echo '<script> 
            document.addEventListener("DOMContentLoaded", function() {
                console.log(" ' . $sender_id . '");
                console.log(" ' . $receiver_id . '");
                let text = document.createElement("p");
                let img = document.createElement("img");
                let div = document.querySelector(".add-friend");
                img.src = "img/iconaddfriend.png";
                img.id = "icon-add-friend-profile";
                text.id = "text-add-friend";
                text.textContent = "Odeslat žádost";
                div.appendChild(img)
                div.appendChild(text)

                img.style.width = "15px";
                img.style.height = "15px";
                text.style.marginLeft = "0px";
                text.style.fontSize = "13px";

                div.addEventListener("click", function(){
                    $.ajax({
                        url: "includes/sendfriendrequest.inc.php",
                        method: "POST",
                        data: {id_sender: ' .$sender_id. ', id_receiver: ' .$receiver_id. '},
                        success: function(response) {
                            console.log(response);
                            let divAction = document.querySelector(".friend-req-action");
                            let textAction = document.querySelector("#friend-req-action-text");
                            textAction.textContent = "Žádost o přátelství byla odeslána."
                            
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
            });
        </script>';
    }
?>  

    <div class="profile">
        <div class="timeline">
            <?php
            if($id != $id_logged)
            {
                echo '<div class="send-message">
                <img src="img/iconmessage.png" alt="" id="icon-send-message">
                <p id="text-send-message">Poslat zprávu</p>
                </div>';
            }
            else
            {
                echo '<div class="add-profile-desc" style="background-color:#eaeaea; position:absolute;margin-left:850px;margin-top: 20px;    width: 130px;
                height: 35px; border-radius: 3px; font-size: 14px;background-color: rgba(191,191,191,0.3);">
                <p id="text-add-description" style="position: absolute;    margin-left: 22px;
                margin-top: 10px;
                font-weight: bold;
                font-size:13px;">Add Description</p></div>';

            }
            ?>
            <div class="add-friend">
            </div>
            
            <div class="profile-picture-buttons">
                <button id="delete-image">Vymazat</button>
            </div>
            <img src="img/tl2.png" id="timeline-img" alt="">
            <div class="profile-picture">
                <img src="img/photoicon.png" alt="" id="photoicon">
                <form action="includes/saveimgsql.inc.php" method="post" enctype="multipart/form-data">
                    <input type="file" id="file-input" name="pp" style="display: none;">
                    <input type="submit" id="submit-img-btn" name="submit">
                </form>
            </div>

            <div class="profile-username">

            </div>
            
        </div>
        <div class="user-more">
            <div class="text-area-div" style="display: none">
                <div class="text-area-btn-div" style="background-color:#eaeaea; position:absolute;margin-left:60px;margin-top: 20px;    width: 130px;
                height: 35px; border-radius: 3px; font-size: 14px;background-color: rgba(191,191,191,0.8)">
                <p id="text-add-description" style="position: absolute;    margin-left: 22px;
                margin-top: 10px;
                font-weight: bold;
                font-size:13px;">Set Description</p></div>
                </div>
                <textarea id="text-area-profile" style="display: none; 
                margin-top: 60px; margin-left: 60px; position:absolute"> Insert here your description.</textarea>

            </div>

            <div class="desc-area" style="width:500px;height:450px;position:absolute;
            margin-top:400px;margin-left:450px; background-color:blanchedalmond; z-index:10000">
                <p id="desc-profile-text"></p>
            </div>

        </div>
    </div>
        

<script>
    document.addEventListener("DOMContentLoaded", function(){
        getProfileDescription();
        let sendMessageDiv = document.querySelector(".send-message")
    })
    let vulva = document.querySelector(".text-area-div");
    let textArea = document.querySelector("#text-area-profile");
    document.addEventListener("DOMContentLoaded", function(){
        let addDesc = document.querySelector(".add-profile-desc")
        if(addDesc)
        {
            addDesc.addEventListener("click", function(){
                console.log("zmacknutooooooooooo")
                let sendBtn = document.querySelector(".text-area-div")

                if(textArea.style.display === "block")
                {
                    textArea.style.display = "none";
                }
                else if(textArea.style.display === "none")
                {
                    textArea.style.display = "block";
                }
                if(sendBtn.style.display === "block")
                {
                    sendBtn.style.display = "none";
                }
                else if(sendBtn.style.display === "none")
                {
                    sendBtn.style.display = "block";
                }
            })
        }
    })
    function getProfileDescription()
    {
        $.ajax({
            url: 'includes/getprofiledesc.inc.php',
            method: 'POST',
            data: {user_id: logged_id},
            success: function(response) {
                let data = response;
                let ptext;
                if(response)
                {
                    ptext = document.querySelector("#desc-profile-text")
                    let to = JSON.parse(data);
                    ptext.textContent = to.user_desc;
                    textArea.style.display = "none";
                    vulva.style.display = "none";
                }
                console.log(data);
            },
            error: function(xhr, status, error) {
            console.error(error);
            console.error(xhr);
            console.error(status);
            }
        });
    }

    let sendBtn = document.querySelector(".text-area-btn-div")
    sendBtn.addEventListener("click", function()
    {
        console.log("tap");
        let descValue = textArea.value;
        let logged_id = <?php echo $id_logged ?>;
        
        $.ajax({
            url: 'includes/insertprofiledesc.inc.php',
            method: 'POST',
            data: {desc: descValue,
                    id_logged: logged_id},
            success: function(response) {
                console.log("nic");
                getProfileDescription();

            },
            error: function(xhr, status, error) {
            console.error(error);
            console.error(xhr);
            console.error(status);
            }
        });

    })
    
    
    let urlAll = new URLSearchParams(window.location.search);
    let urlID = urlAll.get('id');

    let sessionID = <?php echo $_SESSION['id'] ?>

    let removedP = document.querySelector(".profile-username-text")
    if(removedP){
        removedP.remove();
    }
    
    let username = document.createElement('p');
    
    username.textContent = "<?php echo $username; ?>"
    let profileUsernameDiv = document.querySelector('.profile-username')
    profileUsernameDiv.appendChild(username);
    username.className = "profile-username-text"
    let photoicon
    photoicon = document.querySelector("#photoicon")
    let profilePictureDiv = document.querySelector(".profile-picture")

    //Najeti mysi do profil.image okna a zobrazeni tlacitek (add friend..)
    addFriendDiv = document.querySelector(".add-friend");
    followUserDiv = document.querySelector(".follow-user");

    if(sessionID == urlID){
        console.log("fokume");
        profilePictureDiv.addEventListener("mouseenter", function(){
            photoicon.style.display = "block"
        });

        profilePictureDiv.addEventListener("mouseleave", function(){
            photoicon.style.display = "none";
        });

        addFriendDiv.style.display = "none";
        followUserDiv.style.display = "none";

    }
    document.addEventListener("DOMContentLoaded", function(){
        profilePictureDiv.addEventListener("click", function(){
            console.log("profile clicked");
        })
    })
</script>

<?php
//    echo '<script> 
//     document.addEventListener("DOMContentLoaded", function() { 
//         let photoicon = document.querySelector("#photoicon");
//         photoicon.addEventListener("click", function(){
//             console.log("now");
//         })

//     });
//     </script>';
?>

<script>
   
    //Kliknuti na ikonu pro pridani obrazku
    let fileInput = document.querySelector('#file-input')

    photoicon.addEventListener('click', function(){
    console.log("jajaja");
    fileInput.click()
    
    })
    
    let saveButton = document.querySelector('#submit-img-btn')
    let deleteButton = document.querySelector('#delete-image')
    let file
    let imageUrl

    //Nahrani obrazku
    fileInput.addEventListener('change', function(event) {
        
        deleteButton.style.display = "block";
        saveButton.style.display = "block";
        
        file = event.target.files[0]; 
        let reader = new FileReader();
        reader.onload = function(event) {
            let imageDataUrl = event.target.result; 

            let usersImg = document.createElement("img");
            usersImg.id = "users-img";
            usersImg.src = imageDataUrl;
            
            let contDiv = document.querySelector(".profile-picture");
            let defaultimg = document.querySelector("#profile-picture");
            
            defaultimg.remove();
            contDiv.appendChild(usersImg);
            let photoicon
            photoicon = document.querySelector("#photoicon")
            photoicon.style.display = "none";
            
        
        };
        console.log(file);
        reader.readAsDataURL(file); 
    });

    //Vymazani docasneho obrazku a pridani klasickeho
    var urlParams = new URLSearchParams(window.location.search);
    var id_receiver = urlParams.get('id');
    var id_sender = <?php echo $_SESSION["id"]; ?>;
    deleteButton.addEventListener('click', function(){
        
        let contDiv = document.querySelector(".profile-picture");
        let image = document.createElement("img");
        $.ajax({
            url: 'includes/bringimage.inc.php',
            method: 'POST',
            data: {idecko: id_receiver},
            success: function(response) {
                console.log(response);
                if(response.length > 2){
                    newResponse = response.replace(/[\\"]/g, '')
                    image.id = "profile-picture";
                    image.src = "data:image/jpeg;base64," + newResponse;
                    contDiv.appendChild(image);
                    saveButton.style.display = "none";
                    deleteButton.style.display = "none";
                    let usersImg = document.querySelector("#users-img")
                    photoicon.style.display = "block";
                    usersImg.remove() 
                    
                } else {
                    image.id = "profile-picture";
                    image.src = "img/nophotoimg.jpg";
                    contDiv.appendChild(image);
                    saveButton.style.display = "none";
                    deleteButton.style.display = "none";
                    let usersImg = document.querySelector("#users-img")
                    photoicon.style.display = "block";
                    usersImg.remove() 
                }
                
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
    include_once 'footer.php';

?>