<?php
include_once("lib.php");

/**
 * Hermes class
 *
 * Used for communicating with
 * the facebook messenger api
 * and sending and receiving messages to the user.
 *
 * PHP fragment of ARGUS:
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
class Hermes {
    private $name          = "A.R.G.U.S.";
    
    private $convo_id = "";
    
    ### Variables for use with the api
    // Our send message
    private $message;
    
    // The ID of ARGUS
    private $bot_id = 1;
    
    // The URL for our api call
    private $url = "https://www.asyrion.de/Program-O-master/chatbot/conversation_start.php";
    
    // The format for our response
    private $format = "json";
    
    // The ID from the facebook user
    private $sender_id;
    
    /**
     * Function InputMessage:
     *
     * Get the message input from the user
     * remove the spaces and proceed it to our api call.
     *
     * @parameter $message The input message from the user
     * @variable  $json    The returned json object of our api call
     * @return    $result  The response from ARGUS to the user
     */
    public function InputMessage($message, $sender_id) {
        
        // Needed for creating a personel convo_id
        $this->sender_id = $sender_id;
        
        $message = str_replace(" ", "+", $message);
        
        $json = $this->CallAPI($message);
        
        // Get the convo id for further use
        $this->GetConvoID($json);
        
        $result = $json->botsay;
        
        $result = $this->CheckKeywords($result);
        
        return $result;
    }
    
    /**
     * Function CallAPI:
     *
     * Used to connect to the api of ARGUS and get
     * the response for the users input.
     *
     * @parameter $message the input message from the user
     * @variable  $curl Our cURL connection object
     * @return    $json The result of our api call query
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
        
        $this->GetConvoID($json);
        
        return $json;
    }
    
    /**
     * Get the id of our conversation 
     *
     * Check if the convo_id is readable from a file
     * ... if not write the convo_id file
     */
    private function GetConvoID($json) {
        if(!$this->ReadConvoFile()) {
            $this->convo_id = $this->WriteConvoFile();
            
            // the return value is not needed
            return TRUE;
        }else{
            // the return value is not needed
            return FALSE;
        }
        
    }
    
    /**
     * Function CheckKeywords
     *
     * Used to chck for possible keywords and then proceed these keywords.
     *
     * @parameter $message The input message from the user
     *
     * @return    $output Our response from ARGUS
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
     * @parameter $message Our message from the user
     *
     * @return    $message Our output message for ARGUS
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
     * Function ReadConvoFile
     *
     * Read the convo file to check if its filled.
     * 
     * @return boolean If our convo_id is readable or not
     */
    private function ReadConvoFile() {
        $content = file_get_contents("convo_ids/".$this->convo_id.".argus");
        $contents = explode("||", $content);
        
        if($this->convo_id == $contents[1]) {
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * Function WriteConvoFile
     *
     * Write the file that contains our convo_id
     * and fill the attribute of our class.
     *
     * @return $convo_id Our convo_id for the converstion between user and ARGUS
     */
    private function WriteConvoFile() {
        $convo_id = md5("a");
        
        $this->convo_id = substr($convo_id, 0, -7);
        
        $handle = fopen("convo_ids/".$this->convo_id.".argus", "a+");
        
        fwrite($handle, $this->sender_id."||".$this->convo_id);
        
        fclose($handle);
        
        return $this->convo_id;
    }
}
?>
