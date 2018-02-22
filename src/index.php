<?php

	//error_reporting(0);
    $access_token = "EAAbDikW6jKYBAEn8u3LeWXUzbiOq4cYFRZBxCmZC79jvZB7wWTch0tZC5bV6LpsuzzT6DPJWKgbcfi0z81MSQQnBeU6gK1DecoIZBDPXD5RBVAU8C1u0ZBAtQvICSpZBW2oTeR5G95XbCMmn1pkOIzkRpnikwHdioFFZAQXhkpYZATJzcHTeW3I44";

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
    
    ### Variables for database connection
    $host   = "rdbms.strato.de";
    $user   = "U3261345";
    $pass   = "Dohvakijn1996";
    $dbname = "DB3261345";

    if(mysqli_connect($host, $user, $pass)) {
        $datalink = mysqli_connect($host, $user, $pass);
    }else{
        echo "Could not connect to MySQL-Database...";
    }
    
    if(!mysqli_select_db($datalink, $dbname)) {
        echo "Could not select database";
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
    include_once("Hermes.class.php");

    $Hermes = new Hermes;
    
    $message_to_reply = $Hermes->InputMessage($message, $sender);
    
    // TODO Remove this crap from here
    if(preg_match("/||USERNAME||/", $message_to_reply)) {
        $query  = "SELECT ";
        $query .= $dbname.".users.user_name";
        $query .= " FROM ".$dbname.".users";
        $query .= " WHERE ".$dbname.".users.id = '95'";
        $row = mysqli_fetch_array(mysqli_query($datalink, $query));
        
        $message_to_reply = str_replace("||USERNAME||", $row["user_name"], $message_to_reply);
    }
    $message_to_reply = str_replace("ue", "ü", $message_to_reply);
    
    echo $message_to_reply;
    
    //API Url
    $url = "https://graph.facebook.com/v2.6/me/messages?access_token=".$access_token;

    //Initiate cURL.
    $ch = curl_init($url) or die("Dead...");

    if(!$ch) {
        echo "<br>-->".curl_error($ch)."<--<br>";
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
