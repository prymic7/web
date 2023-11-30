


<?php

    include_once 'header.php';
    include_once 'includes/dbh.inc.php';
    include_once 'includes/functions.inc.php';
    $nameid = $_SESSION['id'];
    if(!isset($_SESSION["username"])){
        setcookie("redirect", "calendar", time() + 3600, "/");
        // header("location: loginpage.php?error=nologincalendar");
        
    }
    
?>




        
        <div id="container">
            <div id="calheader">
                <div id="monthDisplay"></div>
                <div>
                <button id="backButton">Back</button>
                <button id="nextButton">Next</button>
                </div>
            </div>

            <div id="weekdays">
                <div>Sunday</div>
                <div>Monday</div>
                <div>Tuesday</div>
                <div>Wednesday</div>
                <div>Thursday</div>
                <div>Friday</div>
                <div>Saturday</div>
            </div>

            <div id="calendar"></div>
            </div>

            <div id="newEventModal">
            <h2>New Event</h2>

            <input id="eventTitleInput" placeholder="Event Title" />

            <button id="saveButton">Save</button>
            <button id="cancelButton">Cancel</button>
            </div>

            <div id="deleteEventModal">
            <h2>Event</h2>

            <p id="eventText"></p>

            <button id="deleteButton">Delete</button>
            <button id="closeButton">Close</button>
            </div>

            <div id="modalBackDrop"></div>
        

<?php
    include_once 'footer.php';
?>


