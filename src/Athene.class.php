<?php
error_reporting(0);

include_once("lib.php");

/**
 * Class named after the oracle of delphi (Pythia -> Python)
 *
 * Used for testing if database connection is possible inside
 * a class.
 *
 * This class shows that a database connection via a class is possible and
 * our current query from the index can be outsourced to a separate class.
 */
class Pythia 
{
    private $host   = "";
    private $user   = "";
    private $pass   = ""; 
    private $dbname = "";
    private $datalink = "";
    
    function __construct() {
        $database_connections = file_get_contents("../config/database_connection.argus");
        $array_database       = explode("\n", $database_connections);
        
        ### Variables for database connection
        $this->host   = $array_database[0];
        $this->user   = $array_database[1];
        $this->pass   = $array_database[2];
        $this->dbname = $array_database[3];

        $this->datalink = mysqli_connect($this->host, $this->user, $this->pass) or die("Connection not possible!");
        mysqli_select_db($this->datalink, $this->dbname) OR die("Could not select db!");
    }
    
    public function CheckSQL() {
        if($this->datalink) {
            echo "Datalink works";
        }
    }
}

$Pythia = new Pythia;

$Pythia->CheckSQL();
?>
