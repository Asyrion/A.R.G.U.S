<?php
// Testing exception handling

class New_Exception extends Exception
{
}

class Test
{
    function __construct() 
    {
        
    }
}


try{
    // Unterdruecken von Fehlern durch das @ um dann Fehler abzufangen und das
    // Exception handling einzuleiten
    if(!$inhalt = file_get_contents("test.txt", "a+")) {
        throw new New_Exception("Exception thrown: ", 441);
    }
    echo $inhalt;
}catch(New_Exception $e) {
    echo "Es wurde eine Exception geworfen: ".$e->getMessage()." [Errorcode: ".$e->getCode()."]";
}
?>