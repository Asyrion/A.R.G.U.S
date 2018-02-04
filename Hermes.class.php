<?php
include_once("lib.php");

class Hermes {
    public $name          = "A.R.G.U.S.";
    // public $customer_name = "";
    // public $mood = rand(0,2);
    
    // Store variables for holding if the user was already greeted
    // and other important sidenotes
    public $greeted           = FALSE;
    public $action_in_queque  = FALSE;
    
    ### Variables for analytics ###
    // Set the counter of actions to zero
    public $actions_performed = 0;
    // Set the messags send to zero
    public $messages_send     = 0;
    
    
    private $message;
    
    /**
     * Function InputMessage used to read the message
     * and look for possible keywords suggesting 
     * different things
     *
     * @parameter $message The input message of the user
     * @return boolean True if sucessful, false if not
     */
    public function InputMessage($message) {
        $array_words = explode(" ", $message);
        
        $this->message = $message;
        
        if(!empty($array_words[1])) {
            foreach($array_words as $word) {
                return $this->CheckKeyword($word, $message);
            }
        }else{
            return $this->CheckKeyword($message, $message);
        }
    }
    
    public function CheckKeyword($word, $message) {
        if(!$greeted) {
                return $this->CheckGreeting($word, $message);
        }
        
        if(!$action_in_queque) {
            return $this->CheckAction($word, $message);
        }
        return $this->CheckGreeting($word, $message);
    }
    
    public function GetTime() {
        echo "Test";
    }    
    
    /**
     * Stand: 30.01.2018
     * Funktionen einbauen zur Pruefung ob
     * bereis begrueßt wurde und wieviele Nachrichten bereits
     * gesendet wurden.
     *
     * Schauen ob der Name des Benutzers herausgefunden werden kann 
     * ueber die Sender-ID -> Facebook API
     */
     public function CheckGreeting($word, $message) {
        $output = "";
  
        if(lib_check_greeting($word, $message)) {
            if(preg_match("/ARGUS/i", $message)) {
                $output  = "Mein Name ist A.R.G.U.S. Ich bin der Hausassistent von Roman. ";
            }
            
            if(preg_match("/Hallo/i", $message) || preg_match("/halli/i", $message) || preg_match("/hallö/i", $message)) {
                $output .= "Hallo ... ";
                sleep(1);
                $output .= "Was kann ich für dich tuen?";
            }
            $greeted = TRUE;
        }else if(lib_check_time($word, $message)){
            $output .= "Es ist ".date("H:i")." Uhr, Roman.";
            // return date("d.m.Y H:s:i");
        }
        return $output;
        
        
//         if(!lib_check_greeting($word) OR !lib_check_time($word)) {
//             return "Ein weiser Mann sagte mal: Nur zwei Dinge in Unserem Universum sind unendlich, die ausdehung der Galaxie und die Dummheit des Menschen...ich finde diesen Spruch ziemlich zutreffend.";
//         }
    }
    
    public function CheckAction($word, $message) {
        $output = "";
        if(preg_match("/Wetter/i", $message) || preg_match("/Grad/i",$message) || preg_match("/Celsius/i", $message)) { 
            $output .= 
        }else if(lib_check_time($word, $message)){
            $output .= "Es ist ".date("H:i")." Uhr, Roman.";
            // return date("d.m.Y H:s:i");
        }
        
        return $output;
    }
}
?>
