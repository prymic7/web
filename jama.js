
//Hl. obrazek do home page
let katkabutton = document.querySelector("#jedna")
katkabutton.addEventListener("click", function(){
  window.location.href = "home.php"
})



//Kdyz kliknem mimo vyhledana jmena, tabulka se zavvre
document.addEventListener('click', function(event) {
  let targetElement = event.target; 

 
  if (!targetElement.closest('.returned-users')) {
    let returnedUsersDiv = document.querySelector('.returned-users');
    
    
    if (returnedUsersDiv) {
      returnedUsersDiv.remove();
    }
  }
});


function loadProfileInfo(){
  //
}

let nav = 0;
let clicked = null;
let events = localStorage.getItem('events') ? JSON.parse(localStorage.getItem('events')) : [];

const calendar = document.getElementById('calendar');
const newEventModal = document.getElementById('newEventModal');
const deleteEventModal = document.getElementById('deleteEventModal');
const backDrop = document.getElementById('modalBackDrop');
const eventTitleInput = document.getElementById('eventTitleInput');
const weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

function openModal(date){
  clicked = date;
  $.ajax({
    url: 'calendarscripts/loadevents.inc.php',
    method: 'POST',
    success: function(response) {
      response = JSON.parse(response)
      let vymazat = false;
      for(let i = 0; i < response.length; i++){
        if(response[i]["event_date"] == clicked){
          
          document.getElementById('eventText').innerText = response[i]["event_name"];
          deleteEventModal.style.display = 'block';
          vymazat = true;
        }
        backDrop.style.display = 'block';
      }
      if(vymazat){
        deleteEventModal.style.display = 'block';
      } else {
        newEventModal.style.display = 'block';
      }
    },
    error: function(xhr, status, error) {
      
      console.error(error);
      console.error(xhr);
      console.error(status);
    }
  });
}

function load() {
  console.log("LOADED")
  
  const dt = new Date();

  if (nav !== 0) {
    dt.setMonth(new Date().getMonth() + nav);
  }

  const day = dt.getDate();
  const month = dt.getMonth();
  const year = dt.getFullYear();

  const firstDayOfMonth = new Date(year, month, 1);
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  
  const dateString = firstDayOfMonth.toLocaleDateString('en-us', {
    weekday: 'long',
    year: 'numeric',
    month: 'numeric',
    day: 'numeric',
  });
  const paddingDays = weekdays.indexOf(dateString.split(', ')[0]);

  document.getElementById('monthDisplay').innerText = 
    `${dt.toLocaleDateString('en-us', { month: 'long' })} ${year}`;

  calendar.innerHTML = '';

  
  $.ajax({
    url: 'calendarscripts/loadevents.inc.php',
    method: 'POST',
    success: function(response) {
        
        response = JSON.parse(response)
    
        for(let i = 1; i <= paddingDays + daysInMonth; i++) {
          
          const daySquare = document.createElement('div');
          daySquare.classList.add('day');
          const dayString = `${year}-${(month + 1).toString().padStart(2, '0')}-${(i - paddingDays).toString().padStart(2, '0')}`;

          const responseLength = response.length
          if (i > paddingDays) {
            
            daySquare.innerText = i - paddingDays;
            if (i - paddingDays === day && nav === 0) {
              daySquare.id = 'currentDay';
              
              
            }
            
            for(let j = 0; j < responseLength; j++){
              if(response[j]["event_date"] == dayString){
                
                const eventDiv = document.createElement('div');
                eventDiv.classList.add('event');
                eventDiv.innerText = response[j]["event_name"];
                daySquare.appendChild(eventDiv);
              }
              
            }
            daySquare.addEventListener('click', () => openModal(dayString));
          } else {
            daySquare.classList.add('padding');
          }
          calendar.appendChild(daySquare); 
        }
      
    },
    error: function(xhr, status, error) {
      
      console.error(error);
      console.error(xhr);
      console.error(status);
    }
  });
}

function closeModal() {
  eventTitleInput.classList.remove('error');
  newEventModal.style.display = 'none';
  deleteEventModal.style.display = 'none';
  backDrop.style.display = 'none';
  eventTitleInput.value = '';
  clicked = null;
  load();
}

function saveEvent() {
  if (eventTitleInput.value) {
    eventTitleInput.classList.remove('error');
    const eventData = {
      date: clicked,
      title: eventTitleInput.value
    };
    $.ajax({
      url: 'calendarscripts/saveevent.inc.php',
      method: 'POST',
      data: eventData,
      success: function(response) {
        console.log(response);
      },
      error: function(xhr, status, error) {
        
        console.error(error);
        console.error(xhr);
        console.error(status);
      }
    });
    closeModal()
  } else {
    eventTitleInput.classList.add('error');
  }
  
}

function deleteEvent(){
  const eventData = {
    date: clicked,
  };
  $.ajax({
    url: 'calendarscripts/deleteevent.inc.php',
    method: 'POST',
    data: eventData,
    success: function(response) {
      closeModal();
    },
    error: function(xhr, status, error) {
      
      console.error(error);
      console.error(xhr);
      console.error(status);
    }
  });
}

function initButtons() {
  document.getElementById('nextButton').addEventListener('click', () => {
    nav++;
    load();
  });

  document.getElementById('backButton').addEventListener('click', () => {
    nav--;
    load();
  });

  
  document.getElementById('saveButton').addEventListener('click', saveEvent);
  document.getElementById('cancelButton').addEventListener('click', closeModal);
  document.getElementById('deleteButton').addEventListener('click', deleteEvent);
  document.getElementById('closeButton').addEventListener('click', closeModal);
  
}

initButtons();
load();