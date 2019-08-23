<?php

require_once __DIR__ . '/vendor/autoload.php';

//Create MP API object
$MP = new MinistryPlatformAPI\MinistryPlatformTableAPI;

//Load env variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

//Authenticate OAuth for MP
$MP->authenticate(); 

//Get Church Online Platform current event data
$chopDomain = getenv('ChOP_DOMAIN_NAME');
$chopCurrentEventUrl = "https://{$chopDomain}/api/v1/events/current";
$chCurrent = curl_init();
curl_setopt($chCurrent, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chCurrent, CURLOPT_URL, $chopCurrentEventUrl);
$chopCurrentData = curl_exec($chCurrent);
curl_close($chCurrent);
$chopCurrentEventInfo = json_decode($chopCurrentData, true);

//Get Church Online Platform current event title
$chopEventsUrl = "https://{$chopDomain}/api/v1/upcoming_event_times";
$chEvents = curl_init();
curl_setopt($chEvents, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chEvents, CURLOPT_URL, $chopEventsUrl);
$chopEventsData = curl_exec($chEvents);
curl_close($chEvents);
$chopEvents = json_decode($chopEventsData, true);

//Make variables from ChOP data
//Create Event Start and change it to correct time zone
$localTimeZone = getenv('LOCAL_TIME_ZONE');
$eventStartDate = new DateTime($chopCurrentEventInfo['response']['item']['eventStartTime'], new DateTimeZone('UTC'));
$eventStartDate->setTimeZone(new DateTimeZone($localTimeZone));
$eventStart = $eventStartDate->format('Y-m-d H:i:s');

$eventIsLive = $chopCurrentEventInfo['response']['item']['isLive'];

$eventTitle = $chopEvents['response']['items'][0]['eventTitle'];

//Send Data to MP
$eventRecord = [];
$eventRecord[] = ['ChOP_Current_Event_ID' => 1, 'Live' => $eventIsLive, 'Start_Date' => $eventStart, 'Title' => $eventTitle];
$mpPut = $MP->table('ChOP_Current_Event')
            ->select("ChOP_Current_Event_ID, Live, Start_Date, Title")
            ->records($eventRecord)
            ->put();
print_r($mpPut);
print date('m/d/Y h:i:s a', time());

?>