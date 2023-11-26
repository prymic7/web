    
<?php

  
  include_once 'includes/dbh.inc.php';
  include_once 'includes/functions.inc.php';
  include_once 'header.php';

  if(isset($_SESSION["id"])){
    $logged_id = $_SESSION["id"];
  }
  
?>
     


      </div>  
    </div>
    <div class="message-block">

      <div class="message-top-bar">
          <div class="message-online-dot">
            <img src="" alt="" id="message-top-bar-img" style="width:10px; height:10px">
          </div>
          <div class="message-num-of-online">
            <p id="message-top-bar-text"></p>
          </div>
          <div class="click-to-display-menu">
              <img src="img/iconmenu.png" alt="" id="click-to-display-menu-text">
          </div>
          <div class="menu-chat">
            <div class="show-online-friends">
              <p id="show-online-friends-text">Zobrazit přátelé</p>
            </div>
            <div class="show-all-chats">
              <p id="show-all-chats-text">Zobrazit zprávy</p>
            </div>
          </div>
      </div>
      
      <div class="all-messages">
        <div class="all-messages-gadget"></div>
      </div>

      <div class="online-friends">
        
      </div>


      <div class="message-in-chat">
        <div class="message-main-bar">
            <div class="messages-all"></div>
        </div>

        <div class="message-bottom-bar">
            <form action="messagestorage.php" class="form-send-message" method="post">
              <input type="hidden" name="sender_id" value="<?php echo $logged_id ?>" id="hidden1">
              <input type="hidden" name="receiver_id" value="" id="hidden2">
              <input type="text" name="message-text-input" id="message-text-input">
              <input type="image" src="img/iconsendmessage.png" name="notsubbed" id="message-submit-input">
            </form>
        </div>
      </div>


    </div>
    <script src="jama.js"></script>
</body>

</html>


    <script>
      let activeID;


      doDisplayChats = false;

      let main = document.querySelector(".message-main-bar");
      let bottom = document.querySelector(".message-bottom-bar");
      let messagesAllDiv = document.querySelector(".messages-all");
      let allMessages = document.querySelector(".all-messages");
      let allMessagesGadget = document.querySelector(".all-messages-gadget");
      let messageTopBar = document.querySelector(".message-top-bar");
      let messageTopBarText = document.querySelector("#message-top-bar-text");
      let messageTopBarImg = document.querySelector("#message-top-bar-img");
      let messageBlock = document.querySelector(".message-block");
      let showFriends = document.querySelector(".show-online-friends")
      let onlineFriends = document.querySelector(".online-friends");
      let menu = document.querySelector(".menu-chat");
      let allFriendsButton = document.querySelector(".show-all-chats");
      let chat = document.querySelector(".message-in-chat");
      
      let logged_id = <?php echo $logged_id ?>;

      function displayChats(){
        allMessages.style.display = "block";
        allMessagesGadget.style.display = "block";
        allMessagesGadget.innerHTML = "";
        
        while (allMessagesGadget.firstChild) {
          allMessagesGadget.removeChild(parentDiv.firstChild); 
        }

        $.ajax({
            url: "includes/displaychats.inc.php",
            method: "POST",
            data: {logged_id: logged_id},
            
            success: function(response) {
              
              response = JSON.parse(response);
              if(response.length > 0){
                let messagesDiv = document.createElement("div");
                for(let i = 0; i < response.length; i++){
                  let friend_id = response[i][3]
                  let stuffDiv = document.createElement("div");
                  stuffDiv.className = "one-slab-message-stuff"
                  stuffDiv.id = response[i][1];
                  stuffDiv.addEventListener("click", function(){
                    let hiddenInput = document.querySelector("#hidden2")
                    messageTopBarText.textContent = stuffDiv.id
                    hiddenInput.value = friend_id;
                    activeID = friend_id;
                    getChat(friend_id, logged_id);
                    setInterval(function(){
                      getNewMessage(response[i][3], logged_id);
                      
                    },2000);
                    setTimeout(function() {
                      
                      $(".message-main-bar").scrollTop($(".message-main-bar")[0].scrollHeight);
                    }, 100);
                    allMessages.style.display = "none";
                    allMessagesGadget.style.display = "none";
                    chat.style.display = "block"

                  })

                  let usernameTextDiv = document.createElement("div");
                  usernameTextDiv.className = "username-text-div";
                  
                  let messageTextDiv = document.createElement("div");
                  
                  messageTextDiv.classList.add("element-message-slab", "one-slab-message-text")
                  let imageDiv = document.createElement("div");
                  
                  imageDiv.classList.add("element-message-slab", "one-slab-message-img")
                  let usernameDiv = document.createElement("div");
                  
                  usernameDiv.classList.add("element-message-slab", "one-slab-message-username")


                  let usernameP = document.createElement("p");
                  let messageText = document.createElement("p");
                  let profileimage = document.createElement("img");

                  profileimage.id = "all-messages-img";
                  if((i + 1) === response.length){
                    stuffDiv.style.marginBottom = "50px";
                  }
                  
                  stuffDiv.appendChild(imageDiv);
                  usernameTextDiv.appendChild(usernameDiv);
                  usernameTextDiv.appendChild(messageTextDiv);
                  stuffDiv.appendChild(usernameTextDiv);

                  allMessagesGadget.append(stuffDiv)

                  if(response[i][0] !== ""){
                    profileimage.src = "data:image/jpeg;base64," + response[i][0];
                  } else {
                    profileimage.src = "data:image/jpeg;base64," + "/9j/4AAQSkZJRgABAQEBLAEsAAD/4QBWRXhpZgAATU0AKgAAAAgABAEaAAUAAAABAAAAPgEbAAUAAAABAAAARgEoAAMAAAABAAIAAAITAAMAAAABAAEAAAAAAAAAAAEsAAAAAQAAASwAAAAB/+0ALFBob3Rvc2hvcCAzLjAAOEJJTQQEAAAAAAAPHAFaAAMbJUccAQAAAgAEAP/hDIFodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvADw/eHBhY2tldCBiZWdpbj0n77u/JyBpZD0nVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkJz8+Cjx4OnhtcG1ldGEgeG1sbnM6eD0nYWRvYmU6bnM6bWV0YS8nIHg6eG1wdGs9J0ltYWdlOjpFeGlmVG9vbCAxMC4xMCc+CjxyZGY6UkRGIHhtbG5zOnJkZj0naHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyc+CgogPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9JycKICB4bWxuczp0aWZmPSdodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyc+CiAgPHRpZmY6UmVzb2x1dGlvblVuaXQ+MjwvdGlmZjpSZXNvbHV0aW9uVW5pdD4KICA8dGlmZjpYUmVzb2x1dGlvbj4zMDAvMTwvdGlmZjpYUmVzb2x1dGlvbj4KICA8dGlmZjpZUmVzb2x1dGlvbj4zMDAvMTwvdGlmZjpZUmVzb2x1dGlvbj4KIDwvcmRmOkRlc2NyaXB0aW9uPgoKIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PScnCiAgeG1sbnM6eG1wTU09J2h0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8nPgogIDx4bXBNTTpEb2N1bWVudElEPmFkb2JlOmRvY2lkOnN0b2NrOjlmOGM2NzA4LTZjZGItNDE3My1hZmFhLTg2MTMxYjQyYjEyZjwveG1wTU06RG9jdW1lbnRJRD4KICA8eG1wTU06SW5zdGFuY2VJRD54bXAuaWlkOjkzNTNjMzAzLWFhMTktNGY2Yi04MDQwLTFkOTc0OWM1YzU5YjwveG1wTU06SW5zdGFuY2VJRD4KIDwvcmRmOkRlc2NyaXB0aW9uPgo8L3JkZjpSREY+CjwveDp4bXBtZXRhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAo8P3hwYWNrZXQgZW5kPSd3Jz8+/9sAQwAFAwQEBAMFBAQEBQUFBgcMCAcHBwcPCwsJDBEPEhIRDxERExYcFxMUGhURERghGBodHR8fHxMXIiQiHiQcHh8e/9sAQwEFBQUHBgcOCAgOHhQRFB4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4e/8AAEQgBaAFoAwERAAIRAQMRAf/EABsAAQADAQEBAQAAAAAAAAAAAAAEBQYDAgEI/8QAOhABAAIBAgMDCQcCBgMAAAAAAAECAwQRBSExEkFRBhNSYWJxkaGxFCIjMoHB0RU0M3JzgpLh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" ;
                  }
                  
                  usernameP.textContent = response[i][1];
                  messageText.textContent = response[i][2];

                  imageDiv.appendChild(profileimage);
                  usernameDiv.appendChild(usernameP);
                  messageTextDiv.appendChild(messageText);
                }
              } 
            },
            error: function(xhr, status, error) {
                
                console.error(error);
                console.error(xhr);
                console.error(status);
            }
        });
      }

      // setInterval(displayChats, 10000)

      let logOutTimer;
      function resetLogoutTimer(){
        clearTimeout(logOutTimer);
        logoutTimer = setTimeout(logoutUser, 30000000);
      }

      function logoutUser(){
        window.location.href = "includes/logout.inc.php";

      }

      document.addEventListener("click", function() {
        resetLogoutTimer();
      });

      document.addEventListener("keypress", function() {
        resetLogoutTimer();
      });

      resetLogoutTimer();



      let onlineFriendsDiv = document.querySelector(".online-friends");


      messageTopBar.addEventListener("click", function() {
        console.log("fuk")
        displayChats();
        var computedStyle = window.getComputedStyle(messageBlock);
        var bottomValue = computedStyle.getPropertyValue("bottom");

        if (bottomValue === "-5px") {
          messageBlock.style.bottom = "-360px";
        } else if (bottomValue === "-360px") {
          messageBlock.style.bottom = "-5px";
        }
      });



      document.querySelector(".click-to-display-menu").addEventListener("click", function(event) {
        event.stopPropagation();
        
        let computedStyle = getComputedStyle(menu);
        let displayValue = computedStyle.getPropertyValue("display");

        if (displayValue === "none") {
          menu.style.display = "flex";
        } else if (displayValue === "flex") {
          menu.style.display = "none";
        }
      });


      

      
      
      function getOnlineUsers(){
        $.ajax({
          url: 'includes/getonlineusers.inc.php',
          method: 'POST',
          data: {logged_id: logged_id},
          success: function(response) {
            console.log(response)
            response = JSON.parse(response);
            let count = 0;
            for(let i = 0; i < response.length; i++){
              
              count++;
            }
            if(count > 0){
              while(onlineFriendsDiv.firstChild) {
                onlineFriendsDiv.removeChild(onlineFriendsDiv.firstChild);
              }


              allFriendsButton.addEventListener("click", function(){
                displayChats();
                event.stopPropagation();
                console.log("neee");
                allMessages.style.display = "block";
                onlineFriends.style.display = "none";
                menu.style.display = "none";
                chat.style.display = "none";
                messageTopBarText.textContent = count + " lidí je online"
              })

              showFriends.addEventListener("click", function(){
                event.stopPropagation();
                
                let computedStyleMessages = getComputedStyle(allMessages);
                let computedStyleFriends = getComputedStyle(onlineFriends);
                
                allMessages.style.display = "none";
                onlineFriends.style.display = "block";
                menu.style.display = "none";
                
                messageTopBarText.textContent = count + " lidí je online"

              })

              for(let i = 0; i < count; i++){
              
                $.ajax({
                url: 'includes/getuserinfo.inc.php',
                method: 'POST',
                data: {id: response[i]},
                success: function(response2) {


                  response2 = JSON.parse(response2);
                  let username = response2["username"];
                  let profileimage = response2["profileimage"];
                  let id = response2["id"];

                  if(profileimage === ""){
                    profileimage = "/9j/4AAQSkZJRgABAQEBLAEsAAD/4QBWRXhpZgAATU0AKgAAAAgABAEaAAUAAAABAAAAPgEbAAUAAAABAAAARgEoAAMAAAABAAIAAAITAAMAAAABAAEAAAAAAAAAAAEsAAAAAQAAASwAAAAB/+0ALFBob3Rvc2hvcCAzLjAAOEJJTQQEAAAAAAAPHAFaAAMbJUccAQAAAgAEAP/hDIFodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvADw/eHBhY2tldCBiZWdpbj0n77u/JyBpZD0nVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkJz8+Cjx4OnhtcG1ldGEgeG1sbnM6eD0nYWRvYmU6bnM6bWV0YS8nIHg6eG1wdGs9J0ltYWdlOjpFeGlmVG9vbCAxMC4xMCc+CjxyZGY6UkRGIHhtbG5zOnJkZj0naHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyc+CgogPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9JycKICB4bWxuczp0aWZmPSdodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyc+CiAgPHRpZmY6UmVzb2x1dGlvblVuaXQ+MjwvdGlmZjpSZXNvbHV0aW9uVW5pdD4KICA8dGlmZjpYUmVzb2x1dGlvbj4zMDAvMTwvdGlmZjpYUmVzb2x1dGlvbj4KICA8dGlmZjpZUmVzb2x1dGlvbj4zMDAvMTwvdGlmZjpZUmVzb2x1dGlvbj4KIDwvcmRmOkRlc2NyaXB0aW9uPgoKIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PScnCiAgeG1sbnM6eG1wTU09J2h0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8nPgogIDx4bXBNTTpEb2N1bWVudElEPmFkb2JlOmRvY2lkOnN0b2NrOjlmOGM2NzA4LTZjZGItNDE3My1hZmFhLTg2MTMxYjQyYjEyZjwveG1wTU06RG9jdW1lbnRJRD4KICA8eG1wTU06SW5zdGFuY2VJRD54bXAuaWlkOjkzNTNjMzAzLWFhMTktNGY2Yi04MDQwLTFkOTc0OWM1YzU5YjwveG1wTU06SW5zdGFuY2VJRD4KIDwvcmRmOkRlc2NyaXB0aW9uPgo8L3JkZjpSREY+CjwveDp4bXBtZXRhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAo8P3hwYWNrZXQgZW5kPSd3Jz8+/9sAQwAFAwQEBAMFBAQEBQUFBgcMCAcHBwcPCwsJDBEPEhIRDxERExYcFxMUGhURERghGBodHR8fHxMXIiQiHiQcHh8e/9sAQwEFBQUHBgcOCAgOHhQRFB4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4e/8AAEQgBaAFoAwERAAIRAQMRAf/EABsAAQADAQEBAQAAAAAAAAAAAAAEBQYDAgEI/8QAOhABAAIBAgMDCQcCBgMAAAAAAAECAwQRBSExEkFRBhNSYWJxkaGxFCIjMoHB0RU0M3JzgpLh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" 
                  }

                  let onlinePersonSlab = document.createElement("div");
                  let personUsernameDiv = document.createElement("div");
                  let personProfileimageDiv = document.createElement("div");
                  let greenDotDiv = document.createElement("div")

                  let personUsernameText = document.createElement("p");
                  let personProfileimage = document.createElement("img");
                  let greenDot = document.createElement("img")

                  personUsernameText.id = "online-friend-username-text";
                  personProfileimage.id = "online-friend-profileimage-img";
                  onlinePersonSlab.id = username;
                  greenDot.id = "greendot";
                  
                  onlinePersonSlab.className = "online-friend-slab";
                  personProfileimageDiv.className = "online-friend-profileimage";
                  personUsernameDiv.className = "online-friend-username";
                  greenDotDiv.className = "green-dot" ;

                  personUsernameText.textContent = username;
                  personProfileimage.src = "data:image/jpeg;base64," + profileimage;
                  greenDot.src = "img/greendot.png";

                  
                  onlinePersonSlab.addEventListener("click", function(){
                    let main = document.querySelector(".message-main-bar");
                    
                    let hiddenInput = document.querySelector("#hidden2")
                    onlineFriendsDiv.style.display = "none";
                    messageTopBarText.textContent = onlinePersonSlab.id;
                    hiddenInput.value = id;
                    chat.style.display = "block";
                    activeID = id;
                    let friend_id = id
                    getChat(friend_id, logged_id);

                    setTimeout(function() {
                      $(".message-main-bar").scrollTop($(".message-main-bar")[0].scrollHeight);
                    }, 100);
                    
                    
                    setInterval(function(){
                      getNewMessage(friend_id, logged_id);
                      
                    },2000);
                    
                    
                  
                  })

                  

                  personProfileimageDiv.appendChild(personProfileimage);
                  personUsernameDiv.appendChild(personUsernameText);
                  greenDotDiv.appendChild(greenDot);

                  onlinePersonSlab.appendChild(personProfileimageDiv);
                  onlinePersonSlab.appendChild(personUsernameDiv);
                  onlinePersonSlab.appendChild(greenDotDiv);

                  onlineFriendsDiv.appendChild(onlinePersonSlab);

            


                      
                },
                error: function(xhr, status, error) {
                  
                  console.error(error);
                  console.error(xhr);
                  console.error(status);
                }
              });
              
              }
              messageTopBarText.textContent = count + " lidí je online"
              messageTopBarImg.src = "img/greendot.png";
              

            } else {
              messageTopBarText.textContent = " Nikdo není online"
              messageTopBarImg.src = "img/reddot.png";

              noFriendsText = document.querySelector("#no-friends-text");
              if(!noFriendsText){
                let noFriendsText = document.createElement("p");
                noFriendsText.id = "no-friends-text";
                noFriendsText.textContent = "Nikdo z vašich přátel není online"
                onlineFriendsDiv.append(noFriendsText);
              }
              

            }
           
            
          },
          error: function(xhr, status, error) {
            
            console.error(error);
            console.error(xhr);
            console.error(status);
          }
        });
       
      }

      setInterval(function() {
        getOnlineUsers();
      }, 100000);
      getOnlineUsers()

      function countFriendsOnline(){
        let divCount = onlineFriends.getElementsByTagName('div').length;
        return divCount;
      }
      console.log(countFriendsOnline());

      let textInput = document.querySelector("#message-text-input");
      
      document.querySelector(".form-send-message").addEventListener("submit", function(event) {
        event.preventDefault(); 
        console.log("Sent")
        let submitbutton = document.querySelector("#message-submit-input");
        
        
        let formData = new FormData(this);
        console.log(this)
        
        
        $.ajax({
            url: "messagestorage.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                textInput.value = "";

                getChat(activeID, logged_id);

                setTimeout(function() {
                  $(".message-main-bar").scrollTop($(".message-main-bar")[0].scrollHeight);
                }, 30);


            },
            error: function(xhr, status, error) {
                
                console.error(error);
                console.error(xhr);
                console.error(status);
            }
        });

       
      });
      
      function getChat(friend_id, logged_id){
        console.log("sui")
        console.log(friend_id);
        console.log(logged_id)
        while(messagesAllDiv.firstChild) {
          messagesAllDiv.removeChild(messagesAllDiv.firstChild);
        }
        
        
        $.ajax({
            url: "includes/displaymessages.inc.php",
            method: "POST",
            data: {friend_id: friend_id, logged_id: logged_id},
            
            success: function(response) {
              
                response = JSON.parse(response)
                console.log(response);
                for(let i = 0; i < response.length; i++){


                  let message_id = response[i][0];
                  let sender_id = response[i][1];
                  let receiver_id = response[i][2];
                  let message_text = response[i][3];
                  let timestamp = response[i][4];

                  let oneMessageDiv = document.createElement("div");
                  let divForText = document.createElement("div");
                  let oneMessageText = document.createElement("p");

                  oneMessageDiv.className = "one-message-div";
                  divForText.className = "div-for-message";
                  oneMessageText.id = "one-message";
                  if(receiver_id !== logged_id){
                    
                    oneMessageDiv.id = "message-right";
                    
                    
                  } else if(receiver_id === logged_id) {
                    
                    oneMessageDiv.id = "message-left";

                    
                  }


                  oneMessageText.textContent = message_text;
                  divForText.appendChild(oneMessageText);
                  oneMessageDiv.appendChild(divForText);

                  messagesAllDiv.appendChild(oneMessageDiv);

                }
                
            },
            error: function(xhr, status, error) {
                
                console.error(error);
                console.error(xhr);
                console.error(status);
            }
        });
      }

      function getNewMessage(friend_id, logged_id){
        $.ajax({
            url: "includes/getnewmessage.inc.php",
            method: "POST",
            data: {logged_id: logged_id, friend_id: friend_id},
            success: function(response) {
              let newresponse = JSON.parse(response)
              console.log(newresponse)
              if(newresponse){
                getChat(friend_id, logged_id);

                setTimeout(function() {
                  $(".message-main-bar").scrollTop($(".message-main-bar")[0].scrollHeight);
                }, 100);
              }
            },
            error: function(xhr, status, error) {
                
                console.error(error);
                console.error(xhr);
                console.error(status);
            }
        });
      }
      
      
      
    
    </script>




