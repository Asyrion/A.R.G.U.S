<?php
function WriteJSON($json, $array_words) {
    $filename = date("Y-m-d")."_json.json";
    $fp = fopen($filename, "w");
    fwrite($fp, json_decode($array_words));
    fclose($fp);
}

function WriteLOG($logmessage) {
    $fp = fopen("test.txt", "a+");
    fwrite($fp, $logmessage."\r\n");
    fclose($fp);
}

// Output the message of our system
function Output($input) {
    if(!empty($input)) {
        echo $input."<br>";
        return true;
    }else{
        return false;
    }
}

function Greet() {
    $random = rand(0, 3);
    switch($random) {
        case 0:
            echo "Hallo,schön dich zu sprechen!<br>";
        break;
        case 1:
            echo "Hi!<br>";
        break;
        case 2:
            echo "Servus...<br>";
        break;
        case 3:
            echo "Naaaa...<br>";
        break;
    }
}

// Check if an action is implied in our phrase
function ActionKeyword($word, $array_words) {
    $phrase = implode(" ", $array_words);
    
    if(preg_match("/Kannst/i", $phrase) || preg_match("/Können/i", $phrase) || preg_match("/Zeig/i", $phrase) || preg_match("/Öffne/i", $phrase)) {
        echo "Actionkeyword<br>";
        if(preg_match("/heißt/i", $word) OR preg_match("/heißen/i", $word) OR preg_match("/Name/i", $word) OR preg_match("/Name%/i", $word)) {
            echo "Mein Name ist I.C.A.R.U.S<br>";
            WriteLOG("[".date("Y-m-d")."] - Action");
        }
    }
}

// Check if our phrase is a question
function QuestionKeyword($word, $array_words) {
    $phrase = implode(" ", $array_words);
    
    if(preg_match("/\?/", $phrase)) {
//         echo $word."<br>";
        if(preg_match("/heißt/i", $word) OR preg_match("/heißen/i", $word) OR preg_match("/Name/i", $word) OR preg_match("/Name%/i", $word)) {
            echo "Mein Name ist I.C.A.R.U.S<br>";
            WriteLOG("[".date("Y-m-d")."] - Greeting");
        }else if(preg_match("/Spät/i", $word) || preg_match("/Uhr/i", $word) || preg_match("/Zeit/i", $word)) {
            echo date("H:i:s")."<br>";
        }
    }
}

function CheckGreeting($word, $array_words) {
    if(preg_match("/Hallo/i", $word)){
        Greet();
        return true;
    }elseif(preg_match("/Hi/i", $word)){
        Greet();
        return true;
    }elseif(preg_match("/Servus/i", $word)){
        Greet();
        return true;
    }elseif(preg_match("/Hallö*/i", $word)){
        Greet();
        return true;
    }elseif(preg_match("/Moin*/i", $word)){
        Greet();
        return true;
    }else{
        return false;
    }
}

// Check for possible keywords for actions
function CheckKeyWords($word, $array_words) {
    $understood = false;
    if(QuestionKeyword($word, $array_words)){
        $understood = true;
    }elseif(CheckGreeting($word, $array_words)) {
        $understood = true;
    }elseif(ActionKeyword($word, $array_words)){
        $understood = true;
    }
    
    if(!$understood){
        return false;
    }else{
        return true;
    }
}

// Explode message in to single words
function ExplodeWords($message) {
    $array_words = explode(" ", $message);
    $count_words = count($array_words);
    
    $not_understood = 0;
    
    foreach($array_words as $number => $word) {
//         echo $word."<br>";
        // CheckKeyWords($word, $array_words);
        if(!CheckKeyWords($word, $array_words)) {
            $not_understood++;
        }
    }
    
//     if($not_understood == $count_words) {
//         echo "Entschuldigung, das habe ich nicht verstanden.<br>";
//     }
    
    /*
    echo "<pre>";
    var_dump($array_words);
    echo "</pre>";
    */
}

// Check if the message is a greeting - deprecated
/*
function checkGreeting($message) {
    $output = "";
    $match  = FALSE;
    if(preg_match("/Hallo/i", $message)) {
        $match = TRUE;
        $output = "Hi, Namenloser";
    }elseif(preg_match("/Hi/i", $message)){
        $match = TRUE;
        $output = "Hallo Namenloser";
    }
    
    if($match) {
        return $output;
    }
}
*/ 
?>
