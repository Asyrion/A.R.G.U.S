<?php
// Legt fest welche API getestet werden soll (Unit-Tests)
$api_test = "WolframAlpha";

/**
 * Erster Versuch einen Chatbot
 * einzubinden ueber ein normales 
 * Formular. -- deprecated
 */
// include("chatbot.php");
// 
// // Chatter Mathatter
// if($_SERVER["REQUEST_METHOD"] == "POST") {
//     // echo $_POST["message"];
//     if(count($_POST) > 0) {
//         ExplodeWords($_POST["message"]);
//     }
//     
//     if(isset($_POST["reload"])) {
//         unset($_POST);
//     }
// }

/**
 * OpenWeatherMap-API Calls
 *
 * Eine kleine Einfuehrung in die Moeglichkeiten
 * mit der OpenWeatherMaps-API. Nutzbar fuer ARGUS
 */
## BEGIN ##
$_GET["city"] = "Emden";

$city = $_GET['city'];

$county_code = "DE";

$zip_code = "26725";

$api_key = "2cfc63fb094eb3a9468c4dd55945f9b5";

// Can bey extracted from previous call
$lat =  "53.35";
$lon =  "7.19";

$location = $lat.",".$lon;

if(isset($city) AND $api_test == "OpenWeatherMap")
{
    // Forecast Call
    $url = 'http://api.openweathermap.org/data/2.5/forecast?q='.strtoupper(urlencode($city)).','.$county_code.'&APPID='.$api_key;
    
    // Weather Call
    $url = 'http://api.openweathermap.org/data/2.5/weather?q='.strtoupper(urlencode($city)).','.$county_code.'&APPID='.$api_key;
    
    // Call by ZIP-Code
    $url = 'http://api.openweathermap.org/data/2.5/weather?zip='.strtoupper(urlencode($zip_code)).','.$county_code.'&APPID='.$api_key;
    
    // UV-Index Call = benoetigt latitude und longitude des Ortes
    $url = 'http://api.openweathermap.org/data/2.5/uvi?APPID='.strtoupper(urlencode($api_key)).'&lat='.$lat.'&lon='.$lon;
    
    // Carbon Monoxide Call (Pollution) = benoetigt location format (location = lat, lon)
    // $url = 'http://api.openweathermap.org/pollution/v1/co/'.$location.'/'.date("Y-m-d\TH:i:s").'Z.json?APPID='.strtoupper(urlencode($api_key));
    
    echo date("Y-m-d\TH:i:s");
    echo "<br>2004-06-14T23:34:30";
    echo "<br>".$url;
    // Gerne auch: Weather Triggers einbauen - bleibt aber erst einmal Zukunftsmusik
    
    $curl = curl_init();
    $headers = array();
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    $json = curl_exec($curl);
    curl_close($curl);
    
    $data = json_decode($json);
 
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
  
    if(!empty($data->name)) {
    ?>
    <div>
       Stadt: <strong><?php echo $data->name ?></strong><br />
       Aktuell:  <strong><?php echo number_format($data->main->temp - 273.15, 1, ',', '') ?> ° C </strong><br />
       Temperatur (heute):<br />
       min. <?php echo number_format($data->main->temp_min - 273.15, 1, ',', '') ?> ° C<br />
       max. <?php echo number_format($data->main->temp_max - 273.15, 1, ',', '') ?> ° C
    </div>
    <?php
    }
}
## END ##

/**
 * WolframAlpha-API Calls
 * 
 * Eine kleine Einfuehrung in die 
 * Nutzungsmoeglichkeiten der WolframAlpha-API
 */
if($api_test == "WolframAlpha") {
    $api_key = "P2LGYT-9TLWAKG6RV";
    
    // WolframAlpha - ShortAnswers Call
    $url = "http://api.wolframalpha.com/v1/result?appid=".$api_key."&i=What+did+Tesla+invent%3f&units=metric";
    
    // WolframAlpha - SimpleAPI Call => returns an Image 
    $url = "http://api.wolframalpha.com/v1/simple?appid=".$api_key."&i=What+is+the+price+of+gold%3F";
    
    // WolframAlpha - FullRequest Call
    $url = "http://api.wolframalpha.com/v2/query?appid=".$api_key."&input=population%20of%20france";
    
    // WolframAlpha - ShowSteps Call
    $url = "http://api.wolframalpha.com/v2/query?appid=".$api_key."&input=water%20molecule";
    
    // WolframAlpha - Spoken Results Call
    $url = "http://api.wolframalpha.com/v1/spoken?appid=".$api_key."&i=How+far+is+Los+Angeles+from+New+York%3f&units=metric";
    
    /*
     * Parameters:
     *
     * includepodid = einen Teil der XML-Anfrage filtern
     * format       = nur plaintext oder img Element filtern
     * output       = in welchem Format wird der Ouput erwartet (XML oder JSON)
     */
    
    $curl = curl_init();
    $headers = array();
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    $data = curl_exec($curl);
    curl_close($curl);
    
    echo $data;
    
//     $image = imagecreatefromstring($data);
//     
//     if($image) {
//         header("Content-Type: image/gif");
//         imagepng($image);
//         imagedestroy($image);
//     }else{
//         echo "En Error occurred!";
//     }
}


// The input form, where we can chat with our system
?>
<form method="post" action="json.php" name="form">
    <input type="text" name="message" style="width:225px; height:35px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="reload" value="Reload" style="height:30px;width:100px;" name="reload">
</form>
<script type="text/javascript">
    document.form.message.focus();
</script>