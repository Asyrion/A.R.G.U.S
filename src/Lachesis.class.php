<?php
include_once("lib.php");

/**
 * Class named after the goddess of fate Lachesis
 *
 * Used for communicating with the eToro API
 * 
 * This class handles requests for the trading site 
 * of eToro and gives ARGUS the ability to trade and
 * check values of open trades.
 *
 * This class handles requests for eToro and is 
 * connected to Hermes and Pythia.
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
class Lachesis
{
    // Provided for API access -> Request Header always needed
    private $Ocp_Apim_Subscription_Key = "";
    
    ##################################################
    ## TODO Implement the TRADING API functionality ##
    ##################################################
    // DELETE operation -> Logoff from system
    private $subscription_key = "";
    
    // CREDIT operation -> check actual credits
    private $System = "";
    
    // GET ENTRY ORDER operation -> returns all open orders
    private $System = "";
    
    // POST ENTRY ORDER operation -> returns all open orders
    private $System = "";
    private $Content_Type = "";
    private $InstrumentID = "";
    private $IsBuy = "";
    private $Leverage = "";
    private $Investment = "";
    private $OrderType = "";
    private $ExecutionType = "";
    private $StopLossRate = "";
    private $TakeProfitRate = "";
    private $StopLossPct = "";
    private $TakeProfitPct = "";
    private $LimitRate = "";
    private $IsTrailingStopLoss = "";
    
    // DELETE ENTRY ORDER operation -> cancels open entry order
    private $System = "";
    private $OrderId = "";
    private $Content_Type = "";
    private $OrderType = "";
    
    // GET EQUITY operation -> return current equity of user
    private $System = "";
    
    
    // GET FEES operation -> get current fees
    private $System = "";
    private $InstrumentID = "";
    
    
    // GET TRADE operation -> get current open trades
    private $System = "";
    private $subscription_key = "";
    
    
    // GET TRADE HISTORY operation -> return closed trades of user
    private $System = "";
    private $MinDate = "";
    private $Page = "";
    private $PageSize = "";
    
    
    // PUT TRADE operation -> edit an open trade
    private $System = "";
    private $PositionId = "";
    private $StopLossRate = "";
    private $TakeProfitRate = "";
    private $IsTrailingStopLoss = "";
    
    ###################################################
    ## TODO Implement the METADATA API functionality ##
    ###################################################
    
    
    ###################################################
    ## TODO Implement the RATES API functionality    ##
    ###################################################
    
    
    ###################################################
    ## TODO Implement the SYSTEM API functionality   ##
    ###################################################
    
    
    ###################################################
    ## TODO Implement the USER API functionality     ##
    ###################################################
}
?>
 
