<?php
/***************************************
 * http://www.program-o.com
 * Hermes
 * Version: 2.6.8
 * FILE: teach.php
 * AUTHOR: Elizabeth Perreau and Dave Morton | Roman Harms
 * DATE: 02-23-2018
 * DETAILS: Admin interface for "teaching" an AIML chatbot
 *
 *
 * To teach the bot via the messenger we need a new
 * api call with these variables via POST:
 * $_POST["action"]   -> "teach"
 * $_POST["template"] -> The template of our bot
 * $_POST["pattern"] -> The pattern of our bot
 * $_POST["thatpattern"] -> The that pattern of our bot
 * $_POST["filename"] -> The filename, where to store our constructed AIML
 * $_POST["topic"] -> The topic of the conversation
 *
 * return a TRUE or FALSE status
 ***************************************/
$upperScripts = <<<endScript

    <script type="text/javascript">
<!--
      function showMe() {
        var sh = document.getElementById('showHelp');
        var tf = document.getElementById('teachForm');
        sh.style.display = 'block';
        tf.style.display = 'none';
      }
      function hideMe() {
        var sh = document.getElementById('showHelp');
        var tf = document.getElementById('teachForm');
        sh.style.display = 'none';
        tf.style.display = 'block';
      }
      function showHide() {
        var display = document.getElementById('showHelp').style.display;
        switch (display) {
          case '':
          case 'none':
            return showMe();
            break;
          case 'block':
            return hideMe();
            break;
          default:
            alert('display = ' + display);
        }
      }
//-->
    </script>
endScript;

$post_vars = filter_input_array(INPUT_POST);
$msg = '';

if ((isset ($post_vars['action'])) && ($post_vars['action'] == "teach"))
{
    $msg = insertAIML();
}

$teachContent = $template->getSection('TeachBotForm');
$showHelp = $template->getSection('TeachShowHelp');
$topNav = $template->getSection('TopNav');
$leftNav = $template->getSection('LeftNav');
$main = $template->getSection('Main');
$navHeader = $template->getSection('NavHeader');
$FooterInfo = getFooter();

$errMsgClass = (!empty ($msg)) ? "ShowError" : "HideError";
$errMsgStyle = $template->getSection($errMsgClass);
$noLeftNav = '';
$noTopNav = '';

$noRightNav = $template->getSection('NoRightNav');
$headerTitle = 'Actions:';
$pageTitle = 'My-Program O - Teaching Interface';
$mainContent = $template->getSection('TeachMain');

#$mainContent   = 'Hello!';
$mainTitle = "Chatbot Teaching Interface for $bot_name [helpLink]";

$mainContent = str_replace('[bot_name]', $bot_name, $mainContent);
$mainContent = str_replace('[teach_content]', $teachContent, $mainContent);
$mainContent = str_replace('[showHelp]', $showHelp, $mainContent);
$mainTitle = str_replace('[helpLink]', $template->getSection('HelpLink'), $mainTitle);

/**
 * Function insertAIML
 *
 * @return string
 */
function insertAIML()
{
    //db globals
    global $template, $msg, $post_vars, $dbConn;

    $aimltemplate = trim($post_vars['template']);

    $pattern = trim($post_vars['pattern']);
    $pattern = _strtoupper($pattern);

    $thatpattern = trim($post_vars['thatpattern']);
    $thatpattern = _strtoupper($thatpattern);


    $fileName = trim($post_vars['filename']);
    if (!strstr($fileName, '.aiml')) $fileName .= '.aiml';

    $topic = trim($post_vars['topic']);
    $topic = _strtoupper($topic);
    $bot_id = (isset ($_SESSION['poadmin']['bot_id'])) ? $_SESSION['poadmin']['bot_id'] : 1;

    if (($pattern == "") || ($aimltemplate == ""))
    {
        $msg = 'You must enter a user input and bot response.';
    }
    else
    {
        /** @noinspection SqlDialectInspection */
        $sql = 'INSERT INTO `aiml` (`id`,`bot_id`, `pattern`,`thatpattern`,`template`,`topic`,`filename`) VALUES (NULL, :bot_id, :pattern, :thatpattern, :aimltemplate, :topic, :file)';
        $params = array(
            ':bot_id' => $bot_id,
            ':pattern' => $pattern,
            ':thatpattern' => $thatpattern,
            ':aimltemplate' => $aimltemplate,
            ':topic' => $topic,
            ':file' => $fileName
        );
        $affectedRows = db_write($sql, $params, false, __FILE__, __FUNCTION__, __LINE__);

        if ($affectedRows > 0)
        {
            $msg = "AIML added.";
        }
        else {
            $msg = "There was a problem adding the AIML - no changes made.";
        }
    }

    return $msg;
}
