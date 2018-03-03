<?php
include_once("lib.php");

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
    public function GetUsername() 
    {
        $query  = "SELECT ";
        $query .= $this->dbname.".users.user_name";
        $query .= " FROM ".$this->dbname.".users";
        $query .= " WHERE ".$this->dbname.".users.id = '1' ";
        $row = mysqli_fetch_array(mysqli_query($this->datalink, $query));
        
        // Return our complete result set
        return $row;
    }
    
    /**
     * Function Insert
     * 
     * Insert a new row into one of the tables 
     * of our database.
     */ 
//     public function Insert() 
//     {
//         // Return wether inserting was successfull or not
//         return $boolean;
//     }
}
?>
