<?php
include_once("lib.php");

/**
 * Hermes class
 *
 * Used for communicating with the facebook
 * messenger api, sending and
 * receiving messages to the user.
 *
 * PHP fragment of ARGUS: Hermes
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
 * @version V 0.0.1.3
 */
class Hermes 
{
    private $name     = "A.R.G.U.S.";
    
    private $convo_id = "g767g90d9c8g76s6ss";
    
    ### Variables for use with the api
    // Our send message
    private $message;
    
    // The ID of ARGUS
    private $bot_id = 1;
    
    // The URL for our api call
    private $url = "";
    
    // The format for our response
    private $format = "json";
    
    // The ID from the facebook user
    private $sender_id;
    
    // On constructing Hermes, get the api url 
    // from our configuration file
    function __construct() {
        $this->url = file_get_contents("config/argus_url.argus");
        $this->url = trim($this->url);
    }
    
    /**
     * Function InputMessage:
     *
     * Get the message input from the user
     * remove the spaces and proceed it to our api call.
     *
     * @var    $json    The returned json object of our api call
     * @param  $message The input message from the user
     * @return $result  The response from ARGUS to the user
     */
    public function InputMessage($message, $sender_id) {
        
        // Needed for creating a personal convo_id
        // TODO use the sender and user id to create a
        // convo_id file where the id is stored.
        // PS: The user id doesn't change
        $this->sender_id = $sender_id;
        
        $message = str_replace(" ", "+", $message);
        
        $message = $this->ReverseSpecialchars($message);
        
        $json = $this->CallAPI($message);
        
        // Get the convo id for further use
        // $this->GetConvoID($json);
        
        $result = $json->botsay;
        
        // Transforming all specialchars from our output
        // and encode that sh** in UTF-8
        $result   = $this->Specialchars($result);
        $result   = utf8_encode($result);
        
        // Check for actual Keywords
        // TODO Remove this function from here and check for keywords
        // in the index or another class
        // TODO same needs to be done with the ProceedKeyword function.
        $result = $this->CheckKeywords($result);
        
        return $result;
    }
    
    /**
     * Function CallAPI:
     *
     * Used to connect to the api of ARGUS and get
     * the response for the users input.
     *
     * @var    $curl Our cURL connection object
     * @param  $message the input message from the user
     * @return $json The result of our api call query
     */
    private function CallAPI($message) {
        // This is our url for accessing the api
        // $url = "https://www.asyrion.de/Program-O-master/chatbot/conversation_start.php";
        $this->url .= "?convo_id=".$this->convo_id."&bot_id=".$this->bot_id."&say=".$message."&format=".$this->format."&name=Roman";
        
        $curl = curl_init() or die(curl_error($curl));

        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl) or die(curl_error($curl));

        $json = json_decode($result);
        
        // $this->GetConvoID($json);
        
        return $json;
    }
    
    
    /**
     * Function CheckKeywords
     *
     * Used to chck for possible keywords and then proceed these keywords.
     *
     * @param  $message The input message from the user
     *
     * @return $output Our response from ARGUS
     */
    private function CheckKeywords($message) {
        
        if(preg_match("/||USERNAME||/", $message)) {
            $output = $message;
        }
        
        if(preg_match("/||/",$message) && empty($output)) {
            $output = $this->ProceedKeyword($message);
        }
        return $output;
    }

    /**
     * Function ProceedKeyword
     * 
     * Used for proceeding all sorts of keywords and construct
     * answers or get answers via the api to ARGUS.
     *
     * @param   $message Our message from the user
     *
     * @return  $message Our output message for ARGUS
     */
    private function ProceedKeyword($message) {
        if(preg_match("/||/",$message)) {
            $array = explode("||", $message);
            
            if ($array[1] == "NOW") {
                $output = date("d.m.Y");
                $message = str_replace("||NOW||", $output, $message);
            }
            return $message;
        }
    }
    
    /**
     * Function Specialchars
     *
     * Transform our wrong specialchars from 
     * the output to real german specialchars.
     *
     * List of german specialchars:
     * ü - 129
     * Ü - 154
     * ö - 148
     * Ö - 153
     * ä - 132
     * Ä - 142
     * ß - 225
     *
     * @return $message_to_transform The tranformed message with specialchars
     */
    private function Specialchars($message_to_transform) {
        $message_to_transform = str_replace("ue", chr(129), $message_to_transform);
        $message_to_transform = str_replace("oe", chr(148), $message_to_transform);
        $message_to_transform = str_replace("ae", chr(132), $message_to_transform);
        $message_to_transform = str_replace("ss", chr(225), $message_to_transform);
        
        $message_to_transform = str_replace("Ue", chr(154), $message_to_transform);
        $message_to_transform = str_replace("Oe", chr(153), $message_to_transform);
        $message_to_transform = str_replace("Ae", chr(142), $message_to_transform);
        
        return $message_to_transform;
    }
    
        
    /**
     * Function Specialchars
     *
     * Transform our wrong specialchars from 
     * the output to real german specialchars.
     *
     * @return $message_to_transform The tranformed message with specialchars
     */
    private function ReverseSpecialchars($message_to_transform) {
        $message_to_transform = str_replace(chr(129), "ue", $message_to_transform);
        $message_to_transform = str_replace(chr(148), "oe", $message_to_transform);
        $message_to_transform = str_replace(chr(132), "ae", $message_to_transform);
        $message_to_transform = str_replace(chr(225), "ss ", $message_to_transform);
        
        $message_to_transform = str_replace(chr(154), "Ue", $message_to_transform);
        $message_to_transform = str_replace(chr(153), "Oe", $message_to_transform);
        $message_to_transform = str_replace(chr(142), "Ae", $message_to_transform);
        
        return $message_to_transform;
    }
}
?>
