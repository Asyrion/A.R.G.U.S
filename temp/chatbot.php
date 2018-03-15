<?php
// Testing exception handling

class New_Exception extends Exception
{
}

function test($ja) {
    if(!$ja) {
        throw new New_Exception("Could not load files correctly.", 406);
    }
}

try{
    test(false);
}catch(New_Exception $e) {
    echo "Es wurde eine Exception geworfen: ".$e->getMessage()." [Errorcode: ".$e->getCode()."]";
}

echo "<br><br><br>";


try{
    // Unterdruecken von Fehlern durch das @ um dann Fehler abzufangen und das
    // Exception handling einzuleiten
    if(!@file_get_contents("dd", "a+")) {
        throw new New_Exception("Exception thrown: ", 441);
    }
}catch(New_Exception $e) {
    echo "Es wurde eine Exception geworfen: ".$e->getMessage()." [Errorcode: ".$e->getCode()."]";
}
?>