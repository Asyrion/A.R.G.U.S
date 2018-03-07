<?php
/**
 * Function WriteToLog
 *
 * Used for writing to our logfile
 * to keep track of errors and warnings.
 *
 * @param $content The content to be written in the logfile.
 * @param $type    The type of the message (error, warning etc.)
 * @param $log     The type of logfile the message should be written to.
 */
function WriteToErrorLog($type, $content) {
    switch($type) {
        case "ERROR":
            $handle = fopen("ERRORLOG.txt", "a+");
        break;
        case "LOG":
            $handle = fopen("LOG.txt", "a+");
            $type   = "INFO"; 
        break;
    }
    
    fwrite($handle, "[".date("Y-m-d H:i:s")."] - ".$type." - ".$content."\n");
    fclose($handle);
    
    return TRUE;
}

/**
 * Function FacebookcURL
 *
 * Used for creating cURL calls with the Facebook api
 * and to keep the index slim.
 *
 * @var   $jsonData Our encoded data in JSON format.
 * @param $url      The url our request is aimed at
 * @param $data     The data to send via cURL.
 */
function FacebookcURL($url, $data) {
        $ch = curl_init($url) or die(WriteToErrorLog("ERROR", "Could not initiate cURL.\n"));
        
        // If cURL fails, output the error
        if(!$ch) {
            WriteToErrorLog("ERROR", curl_error($ch)."\n");
        }else{
            WriteToErrorLog("LOG", "Connected successfully!\n");
        }
        
        $jsonData = $data;
        
        //Encode the array into JSON.
        $jsonDataEncoded = $jsonData;

        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);

        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        //Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

        //Execute the request
        $result = curl_exec($ch) or die(WriteToErrorLog("ERROR", "Could not execute your request!.\n"));
        
        return $result;
}
?>
