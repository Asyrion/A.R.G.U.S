<?php
include_once("lib.php");

// CAUTION keywords are defined by || in the answer of ARGUS

/**
 * Hermes class
 *
 * Used for communicating with the facebook
 * messenger api, sending and
 * receiving messages to the user.
 *
 * PHP fragment of ARGUS: Hermes
 *
 * @method string InputMessage()
 * @method string CallAPI()
 * @method string Specialchars()
 * @method string ReverseSpecialchars()
 *
 * @var name       ARGUS
 * @var convo_id   Holds the id for the conversation of the user
 * @var message    The input message
 * @var bot_id     The id of ARGUS
 * @var url        Our url to call the ARGUS-AIML-API
 * @var format     The format we want our result in (JSON)
 * @var sender_id  The facebook-messenger-id of our user
 *
 * @author  Roman Harms
 * @version V 0.0.1.3
 */
class Hermes 
{
    private $name     = "A.R.G.U.S.";
    
    private $convo_id = "";
    
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
    function __construct($sender_id) {
        $this->url = file_get_contents("config/argus_url.argus");
        $this->url = trim($this->url);
        
        // Create a file for our convo_id
        if(!is_file("convo_ids/".$sender_id.".argus") && !empty($sender_id)) {
            $handle = fopen("convo_ids/".$sender_id.".argus", "a+") or WriteToLog("ERROR", "fopen: Failed to create file.\n");
            fwrite($handle, substr(md5($sender_id),0,-7)) or WriteToLog("ERROR", "fwrite: Failed to write to file. Does the file exist?\n");
            fclose($handle) or WriteToLog("ERROR", "fclose: Could not close file stream.\n");
            
            if(filesize("convo_ids/".$sender_id.".argus") <= 0) {
                WriteToLog("ERROR", "No convo_id file was created.\n");
            }else{
                WriteToLog("LOG", "Conversation file created successfully!\n");
            }
        }
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
        // PS: The user id doesn't change
        $this->sender_id = $sender_id;
        
        // Get our convo_id from the sender_id
        $this->convo_id = file_get_contents("convo_ids/".$this->sender_id.".argus");
        
        if(empty($this->convo_id)) {
            WriteToLog("ERROR", "Convo_id is empty!\n");
        }else{
            WriteToLog("LOG", "Convo_id read successfully.\n");
        }
        
        // Transforming all specialchars from our input
        // and encode that sh** in UTF-8
        $message = $this->ReverseSpecialchars($message);
        
        $message = str_replace(" ", "+", $message);
                
        $json = $this->CallAPI($message);
        
        $result = $json->botsay;
        
        // Transforming all specialchars from our output
        // and encode that sh** in UTF-8
        $result   = $this->Specialchars($result);
        
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
        $this->url .= "?convo_id=".$this->convo_id."&bot_id=".$this->bot_id."&say=".$message."&format=".$this->format."&name=Roman";
        
        $curl = curl_init() or die(curl_error($curl));

        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl) or die(curl_error($curl));

        if($result) {
            WriteToLog("LOG", "Connecting to ARGUS successfull!\n");
        }
        
        $json = json_decode($result);
        
        return $json;
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
        $message_to_transform = str_replace("ue", "ü", $message_to_transform);
        $message_to_transform = str_replace("oe", "ö", $message_to_transform);
        $message_to_transform = str_replace("ae", "ä", $message_to_transform);
        $message_to_transform = str_replace("ss", "ß", $message_to_transform);
        
        $message_to_transform = str_replace("Ue", "Ü", $message_to_transform);
        $message_to_transform = str_replace("Oe", "Ö", $message_to_transform);
        $message_to_transform = str_replace("Ae", "Ä", $message_to_transform);
        
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
        $search   = Array();
        $search[] = "ü";
        $search[] = "ö";
        $search[] = "ä";
        $search[] = "ß";
        $search[] = "Ü";
        $search[] = "Ö";
        $search[] = "Ä";
        
        $replace   = Array();
        $replace[] = "ue";
        $replace[] = "oe";
        $replace[] = "ae";
        $replace[] = "ss";
        $replace[] = "Ue";
        $replace[] = "Oe";
        $replace[] = "Ae";
        
        $message = str_replace($search, $replace, $message_to_transform);
        
        return $message;
    }
}
?>
