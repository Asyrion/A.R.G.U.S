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
 * @function string GetUsername() 
 * @function CallAPI        ->
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
    
    //--------------
    ### Table names
    private $tbl_aiml               = $this->dbname.".aiml";
    private $tbl_aiml_userdefined   = $this->dbname.".aiml_userdefined";
    private $tbl_botpersonality     = $this->dbname.".botpersonality";
    private $tbl_bots               = $this->dbname.".bots";
    private $tbl_client_properties  = $this->dbname.".client_properties";
    private $tbl_converstation_log  = $this->dbname.".converstation_log";
    private $tbl_myprogrammo        = $this->dbname.".myprogrammo";
    private $tbl_spellcheck         = $this->dbname.".spellcheck";
    private $tbl_srai_lookup        = $this->dbname.".srai_lookup";
    private $tbl_undefined_defaults = $this->dbname.".undefined_defaults";
    private $tbl_unknown_inputs     = $this->dbname.".unknown_inputs";
    private $tbl_users              = $this->dbname.".users";
    private $tbl_wordcensor         = $this->dbname.".wordcensor";
    
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
        $query .= $tbl_users.".user_name";
        $query .= " FROM ".$tbl_users;
        $query .= " WHERE ".$tbl_users.".sender_id = '".$sender_id."' ";
        $row = mysqli_fetch_array(mysqli_query($this->datalink, $query)) or WriteToLog("ERROR", "Query could not be executed: ".$query."\n");
        
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
//     private function SetName($name) {
//        $query  = "UPDATE "; 
//        $query .= $this->dbname.".botpersonality"
//        $query .= 
//        $query .= 
//     }
    
    /**
     *
     *
     *
     */
    public function Request($message) {
        $tmp = explode("||", $message);
        
        // The value of this will be "SET" or "GET"
        $get_set = $tmp[2];
        
        switch(trim(strtoupper($tmp[3]))) {
            case "NAME":
                WriteToLog("PYTHIA", "Request for name found.\n");
                
//                 if($get_set == "SET") {
//                     $this->SetName($tmp[4]);
//                 }
                
            break;
            case "GENDER":
                WriteToLog("PYTHIA", "Request for gender found.\n");
            break;
            case "BOTMASTER":
                WriteToLog("PYTHIA", "Request for botmaster found.\n");
            break;
            case "VERSION":
                WriteToLog("PYTHIA", "Request for version found.\n");
            break;
            case "AGE":
                WriteToLog("PYTHIA", "Request for age found.\n");
            break;
            case "WEBSITE":
                WriteToLog("PYTHIA", "Request for website found.\n");
            break;
            case "birthplace":
                WriteToLog("PYTHIA", "Request for birthplace found.\n");
            break;
            case "SIZE":
                WriteToLog("PYTHIA", "Request for size found.\n");
            break;
            case "BUILD":
                WriteToLog("PYTHIA", "Request for build found.\n");
            break;
            case "FOOTBALLTEAM":
                WriteToLog("PYTHIA", "Request for footballteam found.\n");
            break;
            case "FAVORITESPORT":
                WriteToLog("PYTHIA", "Request for favoritesport found.\n");
            break;
            case "FAVORITEACTOR":
                WriteToLog("PYTHIA", "Request for favoriteactor found.\n");
            break;
            default:
                WriteToLog("ERROR", "Request could not be executed: ".$tmp[2]."-".$tmp[3]."-".$tmp[4]."\n");
            break;
        }
        return TRUE;
    }
}
?>
