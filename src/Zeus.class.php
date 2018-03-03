<?php
include_once("lib.php");

/**
 * Class named after the greek god of thunder and lightning Zeus.
 *
 * Used for for requests regarding the weather including
 * forecasts and other similar conclusions.
 
 * This class uses the OpenWeatherMap-API to get it's answers.
 *
 * This class handles weather requests and is connected to Hermes.
 *
 *
 * Here some JS might be necessary to get the
 * users location, or the actual position needs to be
 * asked from ARGUS.
 *
 * @function InputMessage   ->
 * @function CallAPI        ->
 * @function GetConvoID     ->
 * @function CheckKeywords  ->
 * @function ProceedKeyword ->
 *
 * @function ReadConvoFile  ->
 * @function WriteConvoFile ->
 *
 * @attribute name          ->
 * @attribute convo_id      ->
 * @attribute message       ->
 * @attribute bot_id        ->
 * @attribute url           ->
 * @attribute format        ->
 * @attribute sender_id     ->
 *
 * @author  Roman Harms
 * @version V 0.0.1.4
 */
class Zeus
{
    // Our key to use with the api
    private $api_key;

    // Forecast Call
    // $url = 'http://api.openweathermap.org/data/2.5/forecast?q='.strtoupper(urlencode($city)).','.$county_code.'&APPID='.$api_key;
    private $url_forecast = "http://api.openweathermap.org/data/2.5/forecast?q=";
    
    // Weather Call
    // $url = 'http://api.openweathermap.org/data/2.5/weather?q='.strtoupper(urlencode($city)).','.$county_code.'&APPID='.$api_key;
    private $url_weather = "http://api.openweathermap.org/data/2.5/weather?q=";
    
    // Call by ZIP-Code
    // $url = 'http://api.openweathermap.org/data/2.5/weather?zip='.strtoupper(urlencode($zip_code)).','.$county_code.'&APPID='.$api_key;
    private $url_zip = "http://api.openweathermap.org/data/2.5/weather?zip=";
    
    // UV-Index Call = benoetigt latitude und longitude des Ortes
    // $url = 'http://api.openweathermap.org/data/2.5/uvi?APPID='.strtoupper(urlencode($api_key)).'&lat='.$lat.'&lon='.$lon;
    private $url_uv = "http://api.openweathermap.org/data/2.5/uvi?APPID=";
    
    // Get our key for using the API
    function __construct() {
        $this->api_key = file_get_contents("config/open_weather_maps.argus");
        $this->api_key = trim($this->url);
    }
}
 

?>
