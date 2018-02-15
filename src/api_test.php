<?php
// First try of accessing the Program-O-API
// through simple calls. Getting ARGUS to respond
// properly through api calls and aiml.

// This is our url for accessing the api
$url = "https://www.asyrion.de/Program-O-master/chatbot/conversation_start.php";

// Variables that can be send to the api
// Not needed conversation id
$convo_id       = "";
// The id of the bot used for the convo
$bot_id         = 1;
// The users input
$say            = "Eine Mango ist eine Frucht";
// HTML, JSON or XML
$format         = "json";
// Must be given from the user
$name           = "Roman";
// Log debug info into file 
$debug_mode     = 1;
// Show errors only = 1
$debug_level    = 1;

$url = $url."?bot_id=1&say=Eine+Mango+ist+eine+Frucht&format=json&name=Roman";

$curl= curl_init() or die(curl_error($curl));

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($curl) or die(curl_error($curl));

echo $result."<br><br>";

$json = json_decode($result);

echo var_dump($json);

echo "<br><br>".$json->convo_id;

curl_close($curl);
?>
