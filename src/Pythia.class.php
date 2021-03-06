<?php
include_once("lib.php");

// CAUTION keywords are defined by || in the answer of ARGUS

/**
 * Class named after the oracle of delphi (Pythia -> Python)
 *
 * Used for testing if database connection is possible inside
 * a class.
 *
 * This class shows that a database connection via a class is possible and
 * our current query from the index can be outsourced to a separate class.
 *
 *
 * This class handles database requests and is connected to Hermes.
 *
 * @function string  GetUsername() 
 * @function boolean SetProperty    ->
 * @function GetConvoID     ->
 * @function CheckKeywords  ->
 * @function ProceedKeyword ->
 *
 * @var host      The host for the mysql connection
 * @var user      The user for the mysql connection
 * @var pass      The password for the mysql connection
 * @var dbname    The name of our used database
 * @var datalink  Our connection object for use with querys
 *
 * @author  Roman Harms
 * @version V 0.0.1.4
 */
class Pythia 
{
    // The host for our connection
    private $host;
    
    // The user for our connection
    private $user;
    
    // The password to our database for the user
    private $pass; 
    
    // The name of our database
    private $dbname;
    
    // The object which stores our
    private $datalink;
    
    // Variables used for the
    // identification of the user
    private $sender_id;
    private $user_name;
    
    //--------------
    ### Table names
    private $tbl_aiml;
    private $tbl_aiml_userdefined;
    private $tbl_botpersonality;
    private $tbl_bots;
    private $tbl_client_properties;
    private $tbl_converstation_log;
    private $tbl_myprogrammo;
    private $tbl_spellcheck;
    private $tbl_srai_lookup;
    private $tbl_undefined_defaults;
    private $tbl_unknown_inputs;
    private $tbl_wordcensor;
    private $tbl_users;
    
    /**
     * Function __construct
     *
     * Creating our database object for further use.
     */
    function __construct() 
    {
        $database_connections = file_get_contents("config/database_connection.argus");
        $array_database       = explode("\n", $database_connections);
        
        ### Variables for database connection
        $this->host   = $array_database[0];
        $this->user   = $array_database[1];
        $this->pass   = $array_database[2];
        $this->dbname = $array_database[3];

        // Define tables for further use
        $this->tbl_aiml                 = $this->dbname.".aiml";
        $this->tbl_aiml_userdefined     = $this->dbname.".aiml_userdefined";
        $this->tbl_botpersonality       = $this->dbname.".botpersonality";
        $this->tbl_bots                 = $this->dbname.".bots";
        $this->tbl_client_properties    = $this->dbname.".client_properties";
        $this->tbl_converstation_log    = $this->dbname.".converstation_log";
        $this->tbl_myprogrammo          = $this->dbname.".myprogrammo";
        $this->tbl_spellcheck           = $this->dbname.".spellcheck";
        $this->tbl_srai_lookup          = $this->dbname.".srai_lookup";
        $this->tbl_undefined_defaults   = $this->dbname.".undefined_defaults";
        $this->tbl_unknown_inputs       = $this->dbname.".unknown_inputs";
        $this->tbl_wordcensor           = $this->dbname.".wordcensor";
        $this->tbl_users                = $this->dbname.".users";
        
        $this->datalink = mysqli_connect($this->host, $this->user, $this->pass) or die("Connection not possible!");
        mysqli_select_db($this->datalink, $this->dbname) OR die("Could not select db!");
    }
    
    /**
     * Function GetUsername
     *
     * Get the username of the current active user
     * from the database.
     */
    public function GetUsername($sender_id) 
    {
        $query  = "SELECT ";
        $query .= $this->tbl_users.".user_name";
        $query .= " FROM ".$this->tbl_users;
        $query .= " WHERE ".$this->tbl_users.".sender_id = '".$sender_id."' ";
        $row = mysqli_fetch_array(mysqli_query($this->datalink, $query)) or WriteToLog("ERROR", "Query could not be executed: ".$query."\n");
        
        $this->sender_id = $sender_id;
        $this->user_name = $row["user_name"];
        
        // Return our complete result set
        return $row["user_name"];
    }
    
    /**
     *
     *
     *
     *
     *
     */
    private function SetProperty($property, $value) {
        $query  = "UPDATE ".$this->tbl_botpersonality." SET "; 
        $query .= $this->tbl_botpersonality.".value = '".$value."'";
        $query .= " WHERE ".$this->tbl_botpersonality.".bot_id = '1' "; // id of ARGUS
        $query .= " and ".$this->tbl_botpersonality.".name = '".$property."' "; // id of ARGUS
        
        if(mysqli_query($this->datalink, $query)) {
            WriteToLog("LOG", "Property ".$property." updated successfully!\n");
            return true;
        }else{
            WriteToLog("ERROR", "Could not update property. Query: ".$query."\n");
            return false;
        }
    }
    
    /**
     * TODO implement functionalitiy of $get_set variable
     *
     */
    public function Request($message) {
        $tmp = explode("||", $message);
        
        // The value of this will be "SET" or "GET"
        $get_set = $tmp[2];
        
        $answer = "";
        
        switch(trim(strtoupper($tmp[3]))) {
            case "NAME":
                WriteToLog("PYTHIA", "Request for name found.\n");
                
                return $this->SetProperty("name", $tmp[4]);
            break;
            case "GENDER":
                WriteToLog("PYTHIA", "Request for gender found.\n");
                
                return $this->SetProperty("gender", $tmp[4]);
            break;
            case "BOTMASTER":
                WriteToLog("PYTHIA", "Request for botmaster found.\n");
                
                return $this->SetProperty("botmaster", $tmp[4]);
            break;
            case "VERSION":
                WriteToLog("PYTHIA", "Request for version found.\n");
                
                $tmp[4] = str_replace(" ", ".", ltrim($tmp[4]));
                
                return $this->SetProperty("version", $tmp[4]);
            break;
            case "AGE":
                WriteToLog("PYTHIA", "Request for age found.\n");
                
                return $this->SetProperty("age", $tmp[4]);
            break;
            case "WEBSITE":
                WriteToLog("PYTHIA", "Request for website found.\n");
                
                return $this->SetProperty("website", $tmp[4]);
            break;
            case "BIRTHPLACE":
                WriteToLog("PYTHIA", "Request for birthplace found.\n");
                
                return $this->SetProperty("birthplace", $tmp[4]);
            break;
            case "SIZE":
                WriteToLog("PYTHIA", "Request for size found.\n");
                
                return $this->SetProperty("size", $tmp[4]);
            break;
            case "BUILD":
                WriteToLog("PYTHIA", "Request for build found.\n");
                
                return $this->SetProperty("build", $tmp[4]);
            break;
            case "FOOTBALLTEAM":
                WriteToLog("PYTHIA", "Request for footballteam found.\n");
                
                return $this->SetProperty("footballteam", $tmp[4]);
            break;
            case "FAVORITESPORT":
                WriteToLog("PYTHIA", "Request for favoritesport found.\n");
                
                return $this->SetProperty("favoritesport", $tmp[4]);
            break;
            case "FAVORITEACTOR":
                WriteToLog("PYTHIA", "Request for favoriteactor found.\n");
                
                return $this->SetProperty("favoriteactor", $tmp[4]);
            break;
            default:
                WriteToLog("ERROR", "Request could not be executed: ".$tmp[2]."-".$tmp[3]."-".$tmp[4]."\n");
            break;
        }
    }
    
    // TODO implement a function for teaching ARGUS 
    // table: aiml
    // fields:
    //  - id             -> The id
    //  - bot_id         -> the bot id of the active bot (1)
    //  - pattern        -> The pattern to match for
    //  - thatpattern    -> The that pattern to look for
    //  - template       -> The answer of the bot
    //  - topic          -> The topic this pattern and template belong to
    //  - filename       -> The name where this aiml is stored
    
    public function Teach($message) {
        // Cut our incoming message
        $array_message = explode("||", $message);
    
        $type    = $array_message[2];
        $message = $array_message[3];
    
        // Create a array to store all the fields
        // which need to be filled
        $field                  = Array();
        
        $fields["bot_id"]       = 1; // ARGUS
        $fields["filename"]     = "teached.aiml";
        
        // Put the right values in our variables
        if($type == "PATTERN")
        {
            // Custom values
            $fields["pattern"]      = $message;
        }
        else if($type == "THAT") 
        {
            $fields["thatpattern"]  = $message;
        }
        else if($type == "TOPIC") 
        {
            $fields["topic"]        = $message;
        }
        else if($type == "TEMPLATE") 
        {
            $fields["template"]     = $message;
        }
        
        // Insert a new row 
        if($type == "PATTERN") 
        {
            $insert  = "INSERT INTO ".$this->tbl_aiml." SET "; 
            $insert .= $this->tbl_aiml.".bot_id      = '".$fields["bot_id"]."',";
            $insert .= $this->tbl_aiml.".pattern     = '".$fields["pattern"]."',";
            $insert .= $this->tbl_aiml.".thatpattern = '',";
            $insert .= $this->tbl_aiml.".template    = '',";
            $insert .= $this->tbl_aiml.".topic       = '',";
            $insert .= $this->tbl_aiml.".filename    = '".$fields["filename"]."'";
            mysqli_query($this->datalink, $insert);
            
            return TRUE;
        }
        // Get the last row and update it
        else
        {
            // Get the id of the last inserted row
            $query  = "SELECT ";
            $query .= $this->tbl_aiml.".id";
            $query .= " FROM ".$this->tbl_aiml;
            $query .= " ORDER BY ".$this->tbl_aiml.".id DESC ";
            $query .= " LIMIT 1 ";
            $row = mysqli_fetch_array(mysqli_query($this->datalink, $query)) or WriteToLog("ERROR", "Query could not be executed: ".$query."\n");
        
            // Update that sh**
            $update  = "UPDATE ".$this->tbl_aiml." SET ";
            
            // Update that column
            if($type == "THAT") 
            {
                $update .= $this->tbl_aiml.".thatpattern = '".$fields["thatpattern"]."'";
            }
            
            // Update topic column
            if($type == "TOPIC" && isset($fields["topic"])) 
            {
                $update .= $this->tbl_aiml.".topic       = '".$fields["thatpattern"]."'";
            }
            
            // Update template column
            if($type == "TEMPLATE" && isset($fields["template"])) 
            {
                $update .= $this->tbl_aiml.".template    = '".$fields["thatpattern"]."'";
            }
            
            $update .= " WHERE ".$this->tbl_aiml.".id    = '".$row["id"]."'";
            mysqli_query($this->datalink, $update);
            
            return TRUE;
        }
    }
    
    /**
     * 
     *
     *
     *
     */
    public function ShowHelp() {
        $message  = "Eine kurze Übersicht über ";
        $message .= "die aktuell möglichen Kommandos für Pythia: \r\n";
        $message .= "DB [FELD] SETZEN [WERT] - Funktion für das setzen und abrufen von ";
        $message .= "verschiedenen Attributen der Persönlichkeit von ARGUS. \r\n";
        $message .= "Dieses Kommando beinhaltet aktuell folgende Felder:\r\n";
        $message .= "NAME \r\n";
        $message .= "GENDER \r\n";
        $message .= "BOTMASTER \r\n";
        $message .= "VERSION \r\n";
        $message .= "AGE \r\n";
        $message .= "WEBSITE \r\n";
        $message .= "BIRTHPLACE \r\n";
        $message .= "SIZE \r\n";
        $message .= "BUILD \r\n";
        $message .= "FOOTBALLTEAM \r\n";
        $message .= "FAVORITESPORT \r\n";
        $message .= "FAVORITEACTOR \r\n";
        
        // Replace all new lines with escaped new lines
        // CAUTION else JSON removes this special characters automatically!!!
        $message = str_replace(array("\r\n", "\r", "\n"), "\\n", $message);
        
        return $message;
    }
}
?>
