<?php
/**
 * Function WriteToLog
 *
 * Used for writing to our logfile
 * to keep track of errors and warnings.
 *
 * @parameter $content The content to be written in the logfile.
 * @parameter $type    The type of the message (error, warning etc.)
 * @parameter $log     The type of logfile the message should be written to.
 */
function WriteToErrorLog($content) {
    $handle = fopen("ERRORLOG.txt", "a+");
    fwrite($handle, "[".date("Y-m-d H:i:s")."] - ERROR - ".$content."\n");
    fclose($handle);
    
    return TRUE;
}
?>
