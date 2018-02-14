<?php
include_once("lib.php");

class Hermes {
    private $name          = "A.R.G.U.S.";
    
    ### Variables for analytics ###
    // Set the counter of actions to zero
    private $actions_performed = 0;
    
    // Set the messags send to zero
    private $messages_send     = 0;
    private $user_id  = "";
    private $convo_id = "akhl54hkkpkf4enl2sv677r7e4";
    
    
    ### Variables for use with the api
    // Our send message
    private $message;
    
    // The ID of ARGUS
    private $bot_id = 1;
    
    // The URL for our api call
    private $url = "https://www.asyrion.de/Program-O-master/chatbot/conversation_start.php";
    
    // The format for our response
    private $format = "json";
    
    
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
    public function InputMessage($message) {
        
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
     * Usedto connect to the api of ARGUS and get
     * the response fpr the users input.
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
        
        return $json;
    }
    
    private function GetConvoID($json) {
        $this->convo_id = $json->convo_id;
    }
    
    private function CheckKeywords($message) {
        
        if(preg_match("/||USERNAME||/", $message)) {
            $output = $message;
        }
        
        if(preg_match("/||/",$message) && empty($output)) {
            $output = $this->ProceedKeyword($message);
        }
        return $output;
    }

    
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
}
?>
