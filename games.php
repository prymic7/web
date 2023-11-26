<?php

include_once 'header.php';

include_once 'includes/functions.inc.php';
include_once 'includes/dbh.inc.php';

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="prepal"></div>
    <div class="game-selection">
        <div class="game-gadget">
            <div class="game-square">
                <div class="game-carEscape">
                    <div class="play-button carescape-button">
                        <div class="game-play-text">
                            <p>Hraj</p>
                        </div>
                    </div>
                    <img src="img/carEscape.png" alt="" id="carescape-picture">
                    <div class="game-awning">
                        <div class="game-name-text">
                            <p>Car Escape</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="game-square">
                <div class="game-koko">
                    <div class="play-button snake-button">
                        <div class="game-play-text">
                            <p>Hraj</p>
                        </div>
                    </div>
                    <img src="img/snakepicture.png" alt="" id="snake-picture">
                    <div class="game-awning">
                        <div class="game-name-text">
                            <p>Snake</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="game-square">
                <div class="game-lolo">
                    <div class="play-button outwar-button">
                        <div class="game-play-text">
                            <p>Hraj</p>
                        </div>
                    </div>
                    <img src="img/outwar.png" alt="" id="outwar-picture">
                    <div class="game-awning">
                        <div class="game-name-text">
                            <p>OutWar</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="game-square">
                <div class="game-bobo"></div>
            </div>
        </div>
    </div>
    
</body>
</html>


<script src="games/snake-game/ready-snake.js"></script>

<script>
        

        let playbutton1 = document.querySelector(".snake-button");
        playbutton1.addEventListener("click", function() {
            window.location.href = "games/snake-game/snake.html"
        });

        let playbutton2 = document.querySelector(".outwar-button");
        playbutton2.addEventListener("click", function() {
            window.location.href = "games/outWar-game/outwar.html"
        });

        let playbutton3 = document.querySelector(".carescape-button");
        playbutton3.addEventListener("click", function() {
            window.location.href = "games/carEscape-game/carEscape.html"
        });
    </script>










<?php

include_once 'footer.php';

?>