<?php

require_once __DIR__ . '/vendor/autoload.php';

//Create MP API object
$MP = new MinistryPlatformAPI\MinistryPlatformTableAPI;

//Load env variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

//Authenticate OAuth for MP
$MP->authenticate(); 

//Get Church Online Platform event data
$chopDomain = getenv('ChOP_DOMAIN_NAME');
$chopEventsUrl = "https://{$chopDomain}/api/v1/events/current";
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $chopEventsUrl);
$chopData = curl_exec($ch);
curl_close($ch);
$chopEventInfo = json_decode($chopData, true);

//Make variables from ChOP data
//Create Event Start and change it to correct time zone
$localTimeZone = getenv('LOCAL_TIME_ZONE');
$eventStartDate = new DateTime($chopEventInfo['response']['item']['eventStartTime'], new DateTimeZone('UTC'));
$eventStartDate->setTimeZone(new DateTimeZone($localTimeZone));
$eventStart = $eventStartDate->format('Y-m-d H:i:s');

$eventIsLive = $chopEventInfo['response']['item']['isLive'];

//Send Data to MP
$eventRecord = [];
$eventRecord[] = ['ChOP_Current_Event_ID' => 1, 'Live' => $eventIsLive, 'Start_Date' => $eventStart];
$mpPut = $MP->table('ChOP_Current_Event')
            ->select("ChOP_Current_Event_ID, Live, Start_Date")
            ->records($eventRecord)
            ->put();
print_r($mpPut);
print date('m/d/Y h:i:s a', time());

?>