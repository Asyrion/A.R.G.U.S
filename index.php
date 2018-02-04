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
    
    /**
    * Some Basic rules to validate incoming messages
    */
//     if(preg_match("[Zeit|Uhrzeit|Jetzt]", strtolower($message))) {
// 
//         // Make request to Time API
//         ini_set("user_agent","Mozilla/4.0 (compatible; MSIE 6.0)");
//         $result = file_get_contents("http://www.timeapi.org/utc/now?format=%25a%20%25b%20%25d%20%25I:%25M:%25S%20%25Y");
//         if($result != "") {
//             $message_to_reply = $result;
//         }
//     } else {
//         $message_to_reply = "Huh! Was hat du gesagt?";
//     }

    $message_to_reply = "";
    /**
    * In der Datei User.class.php wird das Verhalten von ARGUS
    * festgehalten. Hier trifft er seine Entscheidungen.
    *
    *
    */
    include_once("Hermes.class.php");
    
    $Hermes = new Hermes;
    
    $message_to_reply = $Hermes->InputMessage($message);
    
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
    
//     echo "<pre>";
//     echo var_dump($jsonData);
//     echo "<pre>";
//     /* PHP SDK v5.0.0 */
// /* make the API call */
// try {
//   // Returns a `Facebook\FacebookResponse` object
//   $response = $fb->get(
//     '/',
//     '{access-token}'
//   );
// } catch(Facebook\Exceptions\FacebookResponseException $e) {
//   echo 'Graph returned an error: ' . $e->getMessage();
//   exit;
// } catch(Facebook\Exceptions\FacebookSDKException $e) {
//   echo 'Facebook SDK returned an error: ' . $e->getMessage();
//   exit;
// }
// $graphNode = $response->getGraphNode();
// echo $graphNode;
    // echo $jsonData;
    
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
