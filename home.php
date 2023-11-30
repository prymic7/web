<?php

include_once 'header.php';
include_once 'includes/functions.inc.php';
include_once 'includes/dbh.inc.php';

$command = 'powershell.exe -Command "& \"C:\xampp\htdocs\News\my_env\Scripts\python.exe\" \"C:\xampp\htdocs\News\scraping.py\"" 2>&1';
$output = shell_exec($command);


$userIP = $_SERVER["REMOTE_ADDR"];
$apiUrl = 'http://ipinfo.io/' . "2001:718:7:9:c860:ce57:1744:c84c" . '/json';
$response = file_get_contents($apiUrl);
$locationData = json_decode($response, true);

$city = $locationData['city'];
$region = $locationData['region'];
$country = $locationData['country'];

$latitude = $locationData['loc'] ? explode(',', $locationData['loc'])[0] : '';
$longitude = $locationData['loc'] ? explode(',', $locationData['loc'])[1] : '';


$apiKey = ''; //set your weather api key


$weatherNowApiUrl = "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey";
$weatherForecastApiUrl = "http://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$apiKey";


$weatherForecastResponse = file_get_contents($weatherForecastApiUrl);
$weatherForecastData = json_decode($weatherForecastResponse, true);

$weatherNowResponse = file_get_contents($weatherNowApiUrl);
$weatherNowData = json_decode($weatherNowResponse, true);

//NOW --------------------------------------
$temperature = $weatherNowData['main']['temp'];
$weatherDescription = $weatherNowData['weather'][0]['description'];

$temperatureCelsius = $temperature - 273.15;

$weatherNow = array();
$weatherNow[] = round($temperatureCelsius);
$weatherNow[] = $weatherDescription;

//FORECAST----------------------------------

//ONE DAY FROM NOW
function countTmrws($daysPlus){
    $todayDate = date("Y-m-d");
    $explodedTodayDate = explode("-", $todayDate);
    $day = (int) $explodedTodayDate[2] + $daysPlus;
    $month = (int) $explodedTodayDate[1];
    $year = (int) $explodedTodayDate[0];
    $lastDayOfMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    if ($day > $lastDayOfMonth) { 
        $day = 1;
        $month++;  
        if($month > 12){
            $month = 1;
            $year++; 
        }
    }

    $explodedTomorrowDate = [$year, str_pad($month, 2, '0', STR_PAD_LEFT), str_pad($day, 2, '0', STR_PAD_LEFT)];
    $newTomorrowDate = implode("-", $explodedTomorrowDate);
    return $newTomorrowDate;
}
$oneDayForward = array();
$twoDayForward = array();
$threeDayForward = array();
$fourDayForward = array();


function translateDay($day){
    switch($day){
        case "Monday":
            $day = "Pondělí";
            break;
        case "Tuesday":
            $day = "Úterý";
            break;
        case "Wednesday":
            $day = "Středa";
            break;
        case "Thursday":
            $day = "Čtvrtek";
            break;
        case "Friday":
            $day = "Pátek";
            break;
        case "Saturday":
            $day = "Sobota";
            break;
        case "Sunday":
            $day = "Neděle";
    }
    return $day;
}


if (isset($weatherForecastData['list']) && is_array($weatherForecastData['list'])) {
    foreach ($weatherForecastData['list'] as $item) {

        $dt_txt = $item['dt_txt'];
        $date = explode(" ", $dt_txt);
        
        $datum = $date[0];
        $time = $date[1];
    
        if($datum == countTmrws(1) && $time === "15:00:00"){
            $nazevDne = date('l', strtotime($datum));
            $nazevDne = translateDay($nazevDne);
            $tmp = $item["main"]["temp"];
            $tmp = intval(round($tmp - 273.15));
            $desc = $item["weather"][0]["description"];
            $oneDayForward[] = $tmp;
            $oneDayForward[] = $desc;
            $oneDayForward[] = $nazevDne;
           
        }
        if($datum == countTmrws(2) && $time === "15:00:00"){
            $nazevDne = date('l', strtotime($datum));
            $nazevDne = translateDay($nazevDne);
            $tmp = $item["main"]["temp"];
            $tmp = intval(round($tmp - 273.15));
            $desc = $item["weather"][0]["description"];
            $twoDayForward[] = $tmp;
            $twoDayForward[] = $desc;
            $twoDayForward[] = $nazevDne;
            
        }
        if($datum == countTmrws(3) && $time === "15:00:00"){
            $nazevDne = date('l', strtotime($datum));
            $nazevDne = translateDay($nazevDne);
            $tmp = $item["main"]["temp"];
            $tmp = intval(round($tmp - 273.15));
            $desc = $item["weather"][0]["description"];
            $threeDayForward[] = $tmp;
            $threeDayForward[] = $desc;
            $threeDayForward[] = $nazevDne;
        }
        if($datum == countTmrws(4) && $time === "15:00:00"){
            $nazevDne = date('l', strtotime($datum));
            $nazevDne = translateDay($nazevDne);
            $tmp = $item["main"]["temp"];
            $tmp = intval(round($tmp - 273.15));
            $desc = $item["weather"][0]["description"];
            $fourDayForward[] = $tmp;
            $fourDayForward[] = $desc;
            $fourDayForward[] = $nazevDne;
        }
    }

  } else {
    echo "No 'list' data found.";
  }
  

?>

<!DOCTYPE html>
<html>
<head>
    <title>News</title>
</head>
<body>
    
    <div class="news-all-content">
        <div class="left-side-news">
            <div class="weather-container">
                <div class="weather-content">
                    <div class="weather-up-half">
                        <div class="today-text-up">
                            <p>Dnes:</p>
                        </div>
                        <div class="weather-today">
                            <div class="celsius-today"><p class="celsius-today-text"></p></div>
                            <div class="description-today"><img src="" alt="" style="width:180px;height:180px;" class="description-today-img"></div>
                        </div>
                    </div>
                    <div class="weather-down-half">
                        <div class="days-gadget">
                            <div class="weather-days">
                                <div class="weather-days-inline-block">
                                    <div class="weather-day weather-day-1">
                                        <img src="" alt="" id="weather-day-1-img" class="weather-inline-block-img">
                                        <p class="what-day-text weather-tomorrow-text">Zítra</p>
                                        <p class="celsius-down-text celsius-tomorrow-text"></p>
                                    </div>
                                    <div class="weather-day weather-day-2">
                                        <img src="" alt="" id="weather-day-2-img" class="weather-inline-block-img">
                                        <p class="what-day-text weather-twoForward-text"></p>
                                        <p class="celsius-down-text celsius-twoForward-text"></p>
                                    </div>
                                    <div class="weather-day weather-day-3">
                                        <img src="" alt="" id="weather-day-3-img" class="weather-inline-block-img">
                                        <p class="what-day-text weather-threeForward-text"></p>
                                        <p class="celsius-down-text celsius-threeForward-text"></p>

                                    </div>
                                    <div class="weather-day weather-day-4">
                                        <img src="" alt="" id="weather-day-4-img" class="weather-inline-block-img">
                                        <p class="what-day-text weather-fourForward-text"></p>
                                        <p class="celsius-down-text celsius-fourForward-text"></p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-content seznamzpravy-content">
                <div class="articles-wrapper" id="seznamzpravy"></div>
            </div>

            <div class="section-content prozeny-content">
                <div class="articles-wrapper" id ="prozeny"></div>
            </div>
        </div>
        <div class="right-side-news">
            <div class="section-content novinky-content">
                <div class="articles-wrapper" id="novinky"></div>
            </div>
            
            <div class="section-content sport-content">
                <div class="articles-wrapper" id="sport"></div>
            </div> 
        </div>   
    </div>

</body>


</html>

<script>

    //WEATHER----------------------------------------------------------------

    //Stupne celsia u spodnich dnu
    let celsiusTomorrowText = document.querySelector(".celsius-tomorrow-text")
    let celsiusDayTwoText = document.querySelector(".celsius-twoForward-text")
    let celsiusDayThreeText = document.querySelector(".celsius-threeForward-text")
    let celsiusDayFourText = document.querySelector(".celsius-fourForward-text")

    //Cely div jedno dne spodek
    let weatherDayOne = document.querySelector(".weather-day-1");
    let weatherDayTwo = document.querySelector(".weather-day-2");
    let weatherDayThree = document.querySelector(".weather-day-3");
    let weatherDayFour = document.querySelector(".weather-day-4");

    
    let weatherTodayImg = document.querySelector(".description-today-img")
    let weatherTodayText = document.querySelector(".celsius-today-text")

    let whatSecondDay = document.querySelector(".weather-twoForward-text")
    let whatThridDay = document.querySelector(".weather-threeForward-text")
    let whatFourthDay = document.querySelector(".weather-fourForward-text")
    let whatFifthDay = document.querySelector(".weather-fiveForward-text")


    let weatherNow = [<?php echo '"'.implode('","',  $weatherNow ).'"' ?>]
    let oneDayForward = [<?php echo '"'.implode('","',  $oneDayForward ).'"' ?>];
    let twoDayForward = [<?php echo '"'.implode('","',  $twoDayForward ).'"' ?>];
    let threeDayForward = [<?php echo '"'.implode('","',  $threeDayForward ).'"' ?>];
    let fourDayForward = [<?php echo '"'.implode('","',  $fourDayForward ).'"' ?>];
    

    whatSecondDay.textContent = twoDayForward[2];
    whatThridDay.textContent = threeDayForward[2]
    whatFourthDay.textContent = fourDayForward[2]
    // whatFifthDay.textContent = fiveDayForward[2]

    celsiusTomorrowText.textContent = oneDayForward[0] + "°C"
    celsiusDayTwoText.textContent = twoDayForward[0] + "°C"
    celsiusDayThreeText.textContent = threeDayForward[0] + "°C"
    celsiusDayFourText.textContent = fourDayForward[0] + "°C"
    // celsiusDayFiveText.textContent = fiveDayForward[0] + "°C"
    
    
    let allForwardDays = [oneDayForward, twoDayForward, threeDayForward, fourDayForward];

    for(let i = 0; i < allForwardDays.length; i++){
        let desc = allForwardDays[i][1]
        
        let img = document.querySelector("#weather-day-" + (i+1) + "-img")
        
        switch(desc){
        case "clear sky":
            img.src = "img/static/day.svg";
            break;
        case "few clouds":
            img.src = "img/static/cloudy-day-3.svg";
            break;
        case "scattered clouds":
            img.src = "img/static/cloudy-day-3.svg";
            break;
        case "broken clouds":
            img.src = "img/static/cloudy-day-3.svg";
            break;
        case "overcast clouds":
            img.src = "img/static/cloudy.svg";
            break;
        case "mist":
        case "fog":
            img.src = "img/fog.png";
            img.style.transform = "scale(0.8)"
            break;
        case "light rain":
            img.src = "img/static/rainy-4.svg";
            break;
        case "moderate rain":
            img.src = "img/static/rainy-5.svg";
            break;
        case "heavy rain":
            img.src = "img/static/rainy-6.svg";
            break;
        case "light snow":
            img.src = "img/static/snowy-4.svg";
            break;
        case "moderate snow":
            img.src = "img/static/snowy-5.svg";
            break;
        case "heavy snow":
            img.src = "img/static/snowy-6.svg";
            break;
        case "thunderstorm":
            img.src = "img/static/thunder.svg";
            break;
        case "drizzle":
            img.src = "img/static/rainy-2.svg";
            break;
        case "showers":
            img.src = "img/static/rainy-3.svg";
            break;
        

        }
        img.style.transform = "scale(1.8)";
    }

    
    weatherTodayText.textContent = weatherNow[0] + "°C";
    

    switch(weatherNow[1]){
      
        case "clear sky":
            weatherTodayImg.src = "img/static/day.svg";
            break;
        case "few clouds":
            weatherTodayImg.style.marginLeft = "50px";
            weatherTodayImg.src = "img/static/cloudy-day-3.svg";
            break;
        case "scattered clouds":
            weatherTodayImg.style.marginLeft = "50px";
            weatherTodayImg.src = "img/static/cloudy-day-3.svg";
            break;
        case "broken clouds":
            weatherTodayImg.style.marginLeft = "50px";
            weatherTodayImg.src = "img/static/cloudy-day-3.svg";
            break;
        case "overcast clouds":
            weatherTodayImg.src = "img/static/cloudy.svg";
            break;
        case "mist":
        case "fog":
            weatherTodayImg.src = "img/fog.png";
            weatherTodayImg.style.transform = "scale(0.8)"
            break;
        case "light rain":
            weatherTodayImg.src = "img/static/rainy-4.svg";
            break;
        case "moderate rain":
            weatherTodayImg.src = "img/static/rainy-5.svg";
            break;
        case "heavy rain":
            weatherTodayImg.src = "img/static/rainy-6.svg";
            break;
        case "light snow":
            weatherTodayImg.src = "img/static/snowy-4.svg";
            break;
        case "moderate snow":
            weatherTodayImg.src = "img/static/snowy-5.svg";
            break;
        case "heavy snow":
            weatherTodayImg.src = "img/static/snowy-6.svg";
            break;
        case "thunderstorm":
            weatherTodayImg.src = "img/static/thunder.svg";
            break;
        case "drizzle":
            weatherTodayImg.src = "img/static/rainy-2.svg";
            break;
        case "showers":
        case "light intensity shower rain":
            weatherTodayImg.src = "img/static/rainy-3.svg";
            break;

    }
    

    let wrapper = document.querySelector(".wrapper");
    wrapper.style.width = "100%";
    let novinkyArticlesWrapper =  document.querySelector("#novinky")
    let seznamzpravyArticlesWrapper =  document.querySelector("#seznamzpravy")
    let sportArticlesWrapper =  document.querySelector("#sport")
    let prozenyArticlesWrapper =  document.querySelector("#prozeny")
    let articlesWrapper = document.querySelector(".articles-wrapper")
    let newsAllDiv = document.querySelector(".news-all-content");
    let sectionContent = document.querySelector(".section-content");
    let novinkyDiv = document.querySelector(".novinky-content");
    let sportDiv = document.querySelector(".sport-content");
    let seznamzpravyDiv = document.querySelector(".seznamzpravy-content");
    let prozenyDiv = document.querySelector(".prozeny-content");
    
    window.onload = function () {
  $.ajax({
    url: "includes/getscrapeddata.inc.php",
    method: "POST",

    success: function (response) {
      response = JSON.parse(response);
      console.log(response);
    
        for (let i = 0; i < response.length; i++) {
            if (response[i]["img_src"] !== null) { 
                let mainArticleDiv = document.createElement("div");
                let gadgetDiv = document.createElement("div");
                let mainTextDiv = document.createElement("div");
                let textDiv = document.createElement("div");
                let imgDiv = document.createElement("div");

                let imgahref = document.createElement("a");
                let textahref = document.createElement("a");
                let img = document.createElement("img");

                let h3 = document.createElement("h3");

                imgahref.className = "img-a-href";
                textahref.className = "text-a-href"
                img.id = "news-img";


                mainArticleDiv.classList.add("article", "main-article");
                gadgetDiv.classList.add("main-article-content");
                mainTextDiv.classList.add("main-article-above-text");
                textDiv.classList.add("main-article-text");
                imgDiv.classList.add("main-article-img");

                imgahref.setAttribute("href", response[i]["link"])
                textahref.setAttribute("href", response[i]["link"])
                textahref.textContent = response[i]["text"]
                img.src = response[i]["img_src"]
                img.alt = response[i]["img_alt"]

                h3.append(textahref)
                textDiv.append(h3)
                mainTextDiv.append(textDiv)

                imgahref.appendChild(img)
                imgDiv.appendChild(imgahref)

                gadgetDiv.appendChild(imgDiv)
                gadgetDiv.appendChild(mainTextDiv)
                

                mainArticleDiv.appendChild(gadgetDiv)
                
                if (response[i]["link"].includes("www.seznamzpravy")) {
                    seznamzpravyDiv.appendChild(mainArticleDiv)  
                        
                }
                if (response[i]["link"].includes("www.sport")) {
                    sportDiv.append(mainArticleDiv);  
                }
                if (response[i]["link"].includes("www.prozeny")) {
                    prozenyDiv.append(mainArticleDiv);  
                }
                if (response[i]["link"].includes("www.novinky")) {
                    novinkyDiv.append(mainArticleDiv);  
                }   
            } 
        }
        let gadgetDivNovinky = document.createElement("div");
        let gadgetDivSport = document.createElement("div");
        let gadgetDivSeznamZpravy = document.createElement("div");
        let gadgetDivProZeny = document.createElement("div");

        gadgetDivNovinky.classList.add("gadget-articles", "gadget-novinky-div");
        gadgetDivSport.classList.add("gadget-articles", "gadget-sport-div");
        gadgetDivSeznamZpravy.classList.add("gadget-articles", "gadget-seznamzpravy-div");
        gadgetDivProZeny.classList.add("gadget-articles", "gadget-prozeny-div");
        for (let i = 0; i < response.length; i++){ 
            if(response[i]["img_src"] === null){
                
                
                let oneArticle = document.createElement("div");
                let mainDivText = document.createElement("div");
                let h3 = document.createElement("h3");
                let textahref = document.createElement("a");

                
                
                oneArticle.className = "one-article";
                mainDivText.className = "main-div-text";
                textahref.setAttribute("href", response[i]["link"])
                textahref.className = "text-a-href";

                textahref.textContent = response[i]["text"];
                h3.appendChild(textahref)
                oneArticle.appendChild(h3)
               
                

                if (response[i]["link"].includes("www.seznamzpravy")) {
                    gadgetDivSeznamZpravy.appendChild(oneArticle);
                    seznamzpravyArticlesWrapper.append(gadgetDivSeznamZpravy); 
                }
                if (response[i]["link"].includes("www.sport")) {
                    gadgetDivSport.appendChild(oneArticle);
                    sportArticlesWrapper.append(gadgetDivSport);   
                }
                if (response[i]["link"].includes("www.prozeny")) {
                    gadgetDivProZeny.appendChild(oneArticle);
                    prozenyArticlesWrapper.append(gadgetDivProZeny);   
                }
                if (response[i]["link"].includes("www.novinky")) {
                    gadgetDivNovinky.appendChild(oneArticle);
                    novinkyArticlesWrapper.append(gadgetDivNovinky);   
                }  

            }  
            
        }
        seznamzpravyDiv.appendChild(seznamzpravyArticlesWrapper);
        sportDiv.appendChild(sportArticlesWrapper);
        prozenyDiv.appendChild(prozenyArticlesWrapper);
        novinkyDiv.appendChild(novinkyArticlesWrapper)


        
        
        
      
    },
    error: function (xhr, status, error) {
      console.error(error);
      console.error(xhr);
      console.error(status);
    },
  });
};


    
        
</script>



<?php

include_once 'footer.php'







?>
