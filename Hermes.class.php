<?php
include_once("lib.php");

class Hermes {
    private $name          = "A.R.G.U.S.";
    private $customer_name = "";
    
    // Store variables for holding if the user was already greeted
    // and other important sidenotes
    private $greeted           = FALSE;
    private $action_in_queque  = FALSE;
    
    ### Variables for analytics ###
    // Set the counter of actions to zero
    private $actions_performed = 0;
    // Set the messags send to zero
    private $messages_send     = 0;
    
    // Our send message
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
    
    /**
     * Function CheckKeyword used to read the single word and
     * the whole message and look for certain words that trigger
     * an string.
     *
     * @parameter $word    A single word from our input message
     * @parameter $message Our whole input message
     * @return    string   Our output string
     * 
    */
    public function CheckKeyword($word, $message) {
        if(empty($this->customer_name)) {
            return $this->AskForName();
        }
        
        if($this->greeted) {
                return $this->CheckGreeting($word, $message);
        }
        
        if(!$this->action_in_queque) {
            return $this->CheckAction($word, $message);
        }
        return $this->CheckGreeting($word, $message);
    }
    
    /**
     * Function AskForName
     *
     * Function used to ask the user for his
     * name and to use it for further
     * answers.
     */
    public function AskForName() {
        $this->customer_name = "";
        
        return "Ich glaube wir wurden uns noch nicht vorgestellt oder? Würdest du mir deinen Namen verraten:";
    }
    
    /**
     * Stand: 30.01.2018
     * Funktionen einbauen zur Pruefung ob
     * bereis begrueßt wurde und wieviele Nachrichten bereits
     * gesendet wurden.
     *
     * Schauen ob der Name des Benutzers herausgefunden werden kann 
     * ueber die Sender-ID -> Facebook API
     *
     *
     * Function CheckGreeting 
     *
     * This function should be used to look for 
     * a greeting keyword.
     *
     */
     public function CheckGreeting($word, $message) {
        $output = "";
  
        if(preg_match("/hallo/i", $word) || preg_match("/hallö*/i", $word) || preg_match("/halli*/i", $word) || preg_match("/hi*/i", $word) || preg_match("/hey*/i", $word) || preg_match("/ho*/i", $word)) {
            if(preg_match("/ARGUS/i", $message)) {
                $output  = "Mein Name ist A.R.G.U.S. Ich bin der Hausassistent von Roman. ";
            }
            
            if(preg_match("/Hallo/i", $message) || preg_match("/halli/i", $message) || preg_match("/hallö/i", $message)) {
                $output .= "Hallo ... ";
                sleep(1);
                $output .= "Was kann ich für dich tun?";
            }
            $greeted = TRUE;
        }else if(preg_match("/spät*/i", $message) || preg_match("/Uhrzeit*/i", $message) || preg_match("/Zeit*/i", $message) || preg_match("/sagt*Tacho*/i", $message)){
            $output .= "Es ist ".date("H:i")." Uhr.";
        }
        
        return $output;
    }
    
    public function CheckAction($word, $message) {
        $output = "";
        if(preg_match("/Wetter/i", $message) || preg_match("/Grad/i",$message) || preg_match("/Celsius/i", $message)) { 
            $output .= "Welches Wetter wir haben?";
        }else if(lib_check_time($word, $message)){
            $output .= "Es ist ".date("H:i")." Uhr, Roman.";
            // return date("d.m.Y H:s:i");
        }
        return $output;
    }
}
?>
