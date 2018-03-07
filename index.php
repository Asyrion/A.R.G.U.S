<?php
    //error_reporting(0);
    
    require_once("src/lib.php");
    
    // Get the token from our config file
    $access_token = file_get_contents("config/access_token.argus");
    $access_token = trim($access_token);
    
    if(empty($access_token)) {
        WriteToErrorLog("ERROR", "No Access token found!\n");
    }
    
    $verify_token = "fb_argus_client";

    $hub_verify_token = null;

    if(isset($_REQUEST["hub_challenge"])) {
            $challenge = $_REQUEST["hub_challenge"];
            $challenge = trim($challenge, "\\xef\\xbb\\xbf");

            $hub_verify_token = $_REQUEST["hub_verify_token"];
    }

    if ($hub_verify_token === $verify_token) {
            $challenge = str_replace("\ufeff", "", $challenge); 
            echo $challenge;
    }
    
    $input = json_decode(file_get_contents("php://input"), true);

    $sender = $input["entry"][0]["messaging"][0]["sender"]["id"];
    $message = $input["entry"][0]["messaging"][0]["message"]["text"];
    
    // If sender and message are not empty
    // let the user see, that we are typing
    if(!empty($sender) && !empty($message)) {
        WriteToErrorLog("LOG", "Trying to show some action.\n");

        //Get our API-Url from our config file
        $url  = file_get_contents("config/facebook_api_url.argus");
        $url  = trim($url);
        $url .= $access_token;
        
        // The JSON data for our typing_on-event
        $jsonData = '{
            "recipient":{
                "id":"'.$sender.'"
            },
            "sender_action":"typing_on"
        }';
        
        // Let the user see a typing symbol
        FacebookcURL($url, $jsonData);
        
        // The JSON data for our mark_seen-event
        $jsonData = '{
            "recipient":{
                "id":"'.$sender.'"
            },
            "sender_action":"mark_seen"
        }';
        
        // Mark the previous message as seen
        FacebookcURL($url, $jsonData);
    }
    
    $message_to_reply = "";
        
    /**
    * In der Datei Hermes.class.php wird das Verhalten von ARGUS
    * festgehalten. Hier trifft er seine Entscheidungen.
    *
    * Die Hermes Klasse ruft dabei die API von
    * ARGUS auf, welche mithilfe von AIML
    * eine passende Antwort gibt.
    */
    include_once("src/Hermes.class.php");

    $Hermes = new Hermes;
    
    // Testmessage to check if the functions work
    // $message   = "Test";
    // $sender_id = "73849459";
    
    $message_to_reply = $Hermes->InputMessage($message, $sender);

    // Use Pythia to get the username from our database
    if(preg_match("/||USERNAME||/", $message_to_reply)) {
        
        /**
        * In der Datei Pythia.class.php werden Aktionen die
        * die Datenbank betreffen ausgefuehrt.
        *
        * Dies schliesst Select-, Update- sowie Insert-Querys ein.
        */
        include_once("src/Pythia.class.php");
        
        $Pythia = new Pythia;
        
        // TODO implement the sender_id to get the 
        // correct username.
        $username = $Pythia->GetUsername();
        
        $message_to_reply = str_replace("||USERNAME||", $username, $message_to_reply);
    }
    
    echo $message_to_reply;
    
    //Get our API-Url from our config file
    $url  = file_get_contents("config/facebook_api_url.argus");
    $url  = trim($url);
    $url .= $access_token;
    
    //Initiate cURL.
    $ch = curl_init($url) or die(WriteToErrorLog("ERROR", "cURL could not be initiated!\n"));
    
    // If cURL fails, output the error
    if(!$ch) {
        WriteToErrorLog("ERROR", curl_error($ch)."\n");
    }
    
    //The JSON data.
    $jsonData = "{
        'recipient':{
        'id':'".$sender."'
        },
        'message':{
        'text':'".$message_to_reply."'
        }
    }";
    
    //Encode the array into JSON.
    $jsonDataEncoded = $jsonData;

    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);

    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array(‘Content-Type: application/x-www-form-urlencoded’));
    
    //Execute the request
    if(!empty($input["entry"][0]["messaging"][0]["message"])){
        $result = curl_exec($ch);
    }
?>
