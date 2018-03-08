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
        $query .= $this->dbname.".users.user_name";
        $query .= " FROM ".$this->dbname.".users";
        $query .= " WHERE ".$this->dbname.".users.sender_id = '".$sender_id."' ";
        $row = mysqli_fetch_array(mysqli_query($this->datalink, $query)) or WriteToLog("ERROR", "Query could not be executed: ".$query."\n");
        
        // Return our complete result set
        return $row["user_name"];
    }
    
    /**
     *
     *
     *
     */
    public function Request($message) {
        $tmp = explode("||", $message);
        
        switch(trim(strtoupper($tmp[2]))) {
            case "NAME":
                WriteToLog("LOG", "Pythia: Request for name found.\n");
            break;
            default:
                WriteToLog("ERROR", "Pythia: Request could not be executed: ".$tmp[1]."-".$tmp[2]."-".$tmp[3]."\n");
            break;
        }
        return TRUE;
    }
}
?>
