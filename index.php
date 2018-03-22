<?php
    // CAUTION keywords are defined by || in the answer of ARGUS
    
    header('Content-Type: text/html; charset=utf-8');
    
    //error_reporting(0);
    
    // include our exceptions
    require_once("src/Exceptions.class.php");
    // include our library file
    require_once("src/lib.php");
    
    try{
        if(!file_get_contents("config/access_token.argus")) {
            // jumps out of this block to the catch block
            throw new General_Exception("Could not read file: access_token.argus", 101);
        }
        
        // Get the token from our config file
        $access_token = file_get_contents("config/access_token.argus");
        $access_token = trim($access_token);
        
        if(empty($access_token)) {
            throw new General_Exception("No access token found", 701);
        }
    }catch(General_Exception $e) {
        WriteToLog("ERROR", "[Error: ".$e->getCode()."] - ".$e->getMessage()."\n");
    }
    
    $verify_token = "fb_argus_client";

    $hub_verify_token = null;
    
    try{
        if(isset($_REQUEST["hub_challenge"])) {
            $challenge = $_REQUEST["hub_challenge"];
            $challenge = trim($challenge, "\\xef\\xbb\\xbf");

            $hub_verify_token = $_REQUEST["hub_verify_token"];
            
            if(empty($hub_verify_token)) {
                throw new General_Exception("No hub_challenge set", 901);
            }
        }
        
        if ($hub_verify_token === $verify_token) {
            $challenge = str_replace("\ufeff", "", $challenge); 
            echo $challenge;
            
            if(empty($challenge)) {
                throw new General_Exception("hub_verify_token does not match verify_token", 711);
            }
        }
        
    }catch(General_Exception $e) {
        if($e->getCode() == 901) {    
            WriteToLog("ERROR", "[FatalError: ".$e->getCode()."] - ".$e->getMessage()."\n");
        }elseif($e->getCode() == 711){
            WriteToLog("ERROR", "[Error: ".$e->getCode()."] - ".$e->getMessage()."\n");
        }
    }
    
    $input = json_decode(file_get_contents("php://input"), true);

    $sender = $input["entry"][0]["messaging"][0]["sender"]["id"];
    $message = $input["entry"][0]["messaging"][0]["message"]["text"];
    
    // No messages from ARGUS itself, just from external users
    if($sender != "199549497309203" AND !empty($message)) {
        /**
        * Pythia handles database requests.
        *
        * This includes Select-, Update- and Insert-Querys
        *
        * try-catch-block for Pythia
        */
        try {
            if(!include_once("src/Pythia.class.php")) {
                throw new General_Exception("Could not include Pythia", 601);
            }
            
            // Include our Pythia class
            include_once("src/Pythia.class.php");
            // Initiate Pythia
            $Pythia = new Pythia;
            
            if(!$Pythia) {
                throw new General_Exception("Could not initiate Pythia", 611);
            }
            
            if(empty($Pythia->GetUsername($sender))) {
                throw new General_Exception("Could not get username from database", 621);
            }
            // Get the username
            $username = $Pythia->GetUsername($sender);
            
            if(!empty($message)) {
                WriteToLog("MESSAGE", $username." (".$sender."): ".$message."\n");
            }else{
                throw new General_Exception("No message found", 721);
            }
        }catch(General_Exception $e) {
            WriteToLog("ERROR", "[Error: ".$e->getCode()."] - ".$e->getMessage()."\n");
        }
        
        // If sender and message are not empty
        // let the user see that ARGUS is typing
        if(!empty($sender) && !empty($message)) {
            
            try{
                if(!file_get_contents("config/facebook_api_url.argus")) {
                    // jumps out of this block to the catch block
                    throw new General_Exception("Could not read file: facebook_api_url.argus", 101);
                }
                //Get our API-Url from our config file
                $url  = file_get_contents("config/facebook_api_url.argus");
                $url  = trim($url);
                $url .= $access_token;
            
                // The JSON data for our typing_on-event
                $typing_on = '{
                    "recipient":{
                        "id":"'.$sender.'"
                    },
                    "sender_action":"typing_on"
                }';
                
                if(!FacebookcURL($url, $typing_on, "typing_on")) {
                    throw new General_Exception("Facebook event typing_on could not be initiated", 731);
                }
                
                // Let the user see a typing symbol
                FacebookcURL($url, $typing_on, "typing_on");
                
                // The JSON data for our mark_seen-event
                $mark_seen = '{
                    "recipient":{
                        "id":"'.$sender.'"
                    },
                    "sender_action":"mark_seen"
                }';
                
                if(!FacebookcURL($url, $mark_seen, "mark_seen")) {
                    throw new General_Exception("Facebook event mark_seen could not be initiated", 731);
                }
                // Mark the previous message as seen
                FacebookcURL($url, $mark_seen, "mark_seen");
                
            }catch(General_Exception $e) {
                WriteToLog("ERROR", "[Error: ".$e->getCode()."] - ".$e->getMessage()."\n");
            }
        }
        
        $message_to_reply = "";
        
        /**
        * In der Datei Hermes.class.php wird das Verhalten von ARGUS
        * festgehalten. Hier trifft er seine Entscheidungen.
        *
        * Die Hermes Klasse ruft dabei die API von
        * ARGUS auf, welche mithilfe von AIML
        * eine passende Antwort gibt.
        *
        * try-catch-block for Hermes
        */
        try{
            if(!include_once("src/Hermes.class.php")) {
                throw new General_Exception("Could not include Hermes", 501);
            }
            // Include Hermes
            include_once("src/Hermes.class.php");
            
            $Hermes = new Hermes($sender) or die(WriteToLog("ERROR", "Hermes could not be constructed!\n"));
            
            if(!$Hermes) {
                throw new General_Exception("Could not initiate Hermes", 511);
            }
            
            // Testmessage to check if the functions work
            // $message   = "Test";
            // $sender_id = "73849459";
            
            $message_to_reply = $Hermes->InputMessage($message, $sender);
            
            if(!empty($message_to_reply)) {
                WriteToLog("MESSAGE", "ARGUS: ".$message_to_reply."\n");
            }else{
                throw new General_Exception("Hermes: No answer found", 521);
            }
        }catch(General_Exception $e) {
            WriteToLog("ERROR", "[Error: ".$e->getCode()."] - ".$e->getMessage()."\n");
        }
        
        try{
            // Let's check for the Keyword ||DATABASE|| and proceed that request
            // to Pythia, our database handler
            if(strpos($message_to_reply, "||DATABASE||") !== FALSE) {
                // Check if the user asked for a list of commands
                if(strpos($message_to_reply, "||HELP") !== FALSE) {
                    $message_to_reply = $Pythia->ShowHelp();
                }else{
                    // Check if Pythia could execute the request
                    if(!$Pythia->Request($message_to_reply)) {
                        throw new Pythia_Exception("Pythia could not execute request: ".$message_to_reply, 631);
                    }
                    
                    // $Pythia->Request($message_to_reply);
                }
            }/*else{
                // WriteToLog("PYTHIA", "Pythia: No keyword found.\n");
                throw new Pythia_Exception("Pythia: No keyword found.", 641);
            }*/
            
            // TODO throw exceptions here too
            //Get our API-Url from our config file
            $url  = file_get_contents("config/facebook_api_url.argus");
            $url  = trim($url);
            $url .= $access_token;
            
            // TODO throw exceptions here too
            //Initiate cURL.
            $ch = curl_init($url) or die(WriteToLog("ERROR", "cURL could not be initiated!\n"));
            
            // If cURL fails, output the error
            if(!$ch) {
                // WriteToLog("ERROR", curl_error($ch)."\n");
                // TODO change the error code 
                throw new General_Exception(curl_error($ch), 642);
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
        }catch(Pythia_Exception $p) {
            // Go 
            WriteToLog("ERROR", "[Pythia: ".$p->getCode()."] - ".$p->getMessage()."\n");
        }catch(General_Exception $g) {
            // Go
            WriteToLog("ERROR", "[ERROR: ".$g->getCode()."] - ".$g->getMessage()."\n");
        }
    }
?>
