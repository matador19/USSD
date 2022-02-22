<?php
require_once('vendor/autoload.php');

use AfricasTalking\SDK\SMS;
use AfricasTalking\SDK\AfricasTalking;


function successmessage(){
$username = 'Alex1313'; // use 'sandbox' for development in the test environment
$apiKey   = 'b62cf987b8f772041eb3efb4ea224e8364fcc3303dbfa62d9a71e5bfda77abfa';

$AT       = new AfricasTalking($username, $apiKey);

// Get one of the services
$sms      = $AT->sms();

// Use the service
$result   = $sms->send([
    'to'      => '+254743844068',
    'message' => 'Hello World!'

]);
}

//API KEY= 'b62cf987b8f772041eb3efb4ea224e8364fcc3303dbfa62d9a71e5bfda77abfa;'
?>

