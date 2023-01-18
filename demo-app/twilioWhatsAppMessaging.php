<?php

require __DIR__ . "/vendor/autoload.php";

use Twilio\Rest\Client;

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$twilioSid    = getenv('TWILIO_SID');
$twilioToken  = getenv('TWILIO_TOKEN');

$twilio = new Client($twilioSid, $twilioToken);

$message = $twilio->messages
                 ->create(
                     "whatsapp:+5511980868643",
                     array(
                              "body" => "Greetings from Twilio :-) - Aqui é o Manoel",
                              "from" => "whatsapp:+14155238886"
                          )
                 );