<?php
function lib_check_greeting($word) {
    if(preg_match("/hallo/i", $word) || preg_match("/hallö*/i", $word) || preg_match("/halli*/i", $word) || preg_match("/hi*/i", $word) || preg_match("/hey*/i", $word) || preg_match("/ho*/i", $word)) {
        return TRUE;
    }
}

function lib_check_time($word, $message) {
        
    if(preg_match("/spät*/i", $message) || preg_match("/Uhrzeit*/i", $message) || preg_match("/Zeit*/i", $message) || preg_match("/sagt*Tacho*/i", $message)) {
        return TRUE;
    }
}

?>
