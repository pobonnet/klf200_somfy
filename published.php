<?php
/*

This code create a virtual connection in the KLF200. Then we can action/stop the shutter with a relay.
So the next step is to cable a 8 ports IP relay with the wired connections of the klf and to action port 1-2 (up-down) ; 2-3 ....

This is just a POC.

*/



$ip_velux="192.168.1.10";


/* this code is for auth and token */

$lien = 'http://'.$ip_velux.'/api/v1/auth';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $lien);
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, '{"action":"login","params":{"password":"velux123"}}');
$return = curl_exec($curl);
curl_close($curl);
$debut=strpos($return,'"token":"')+9;
$fin=24;
$token = substr($return,$debut,$fin);

/* ************************************************* */


/* this code search the next free connections slot */

$lien = 'http://'.$ip_velux.'/api/v1/connections';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $lien);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token));
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, '{"action":"get"}');
$return = curl_exec($curl);
curl_close($curl);

$actions = json_decode(substr($return,6),true);

$i=0;
for($i=0;$i<10;$i++)
{
	if(!isset($actions["data"][$i]["actionType"]))
	{
		break;
	}
}

$program=$i+1;
$program2=$program+1;
print_r($program);




/* this code delete connections number 1 */
/*
$lien = 'http://'.$ip_velux.'/api/v1/connections';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $lien);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token));
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, '{"action":"remove", "params":{ "id":1 }' );
$return = curl_exec($curl);
curl_close($curl);
print_r($return);
*/








/* this code is to create a connections with a +100 and -100 step */

$id_port_action = "1,2";  //store numbers to control

$lien = 'http://'.$ip_velux.'/api/v1/connections';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $lien);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token));
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, '{"action":"set", "params":{"id":'.$program.',"actionType":"productGroup","channel":3,"originator":1,"silent":false,"blocking":{"time":0,"channels":[]},"successOutputId":null,"errorOutputId":null,"pair":'.$program2.',"node":2,"bind":{"type":"step","value":5},"binds":[{"type":"step","value":100},{"type":"step","value":-100}],"products":['.$id_port_action.']},{"id":'.$program2.',"actionType":"productGroup","channel":3,"originator":1,"silent":false,"blocking":{"time":0,"channels":[]},"successOutputId":null,"errorOutputId":null,"pair":'.$program.',"node":2,"bind":{"type":"step","value":-5},"binds":[{"type":"step","value":-100},{"type":"step","value":100}],"products":['.$id_port_action.']}]' );
$return = curl_exec($curl);
curl_close($curl);
print_r($return);



$lien = 'http://'.$ip_velux.'/api/v1/connections';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $lien);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token));
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, '{"action":"set", "params":{"id":'.$program.',"actionType":"productGroup","channel":3,"originator":1,"silent":false,"blocking":{"time":0,"channels":[]},"successOutputId":null,"errorOutputId":null,"pair":'.$program2.',"node":2,"bind":{"type":"step","value":5},"binds":[{"type":"step","value":100},{"type":"step","value":-100}],"products":['.$id_port_action.']}' );
$return = curl_exec($curl);
curl_close($curl);
print_r($return);


/* ********************************************************************** */





/* this code is for logout */

$lien = 'http://'.$ip_velux.'/api/v1/auth';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $lien);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token));
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, '{"action":"logout","params":{}}');
$return = curl_exec($curl);
curl_close($curl);

/* ******************************* */

?>