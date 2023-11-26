
let snakeModule = null;




async function loadSnakeModule() {
  const response = await fetch('snake.wasm');
  const buffer = await response.arrayBuffer();
  const moduleArgs = {
    wasmBinary: buffer,
    onRuntimeInitialized: () => {
      snake.js
      
    }
  };
  snakeModule = Module(moduleArgs);
}


let playbutton = document.querySelector(".game-play-text");
playbutton.addEventListener("click", function(){
    window.location.href = "games/snake-game/snake.html";
})