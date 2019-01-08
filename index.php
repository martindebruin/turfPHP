<?php

function curlyAction($type,$ID) {
	
  switch ($type) {
  
    case "zone":
      $zonedata = array(array("id" => "$ID"));
	    $zonedataJSON = json_encode($zonedata);
	    $ch_zoneinfo = curl_init('http://api.turfgame.com/v4/zones');
	    curl_setopt($ch_zoneinfo, CURLOPT_POSTFIELDS, $zonedataJSON);
	    curl_setopt($ch_zoneinfo, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    curl_setopt($ch_zoneinfo, CURLOPT_RETURNTRANSFER, true);
	    $zone_result = curl_exec($ch_zoneinfo);
	    $zone_arr = json_decode($zone_result, true);
      return $zone_arr;
      break;
    case "user":
      $data = array(array("name" => "$ID"));
      $data_string = json_encode($data);                                                                                   
      $ch = curl_init('http://api.turfgame.com/v4/users');                                         
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      $arr = json_decode($result, true);
      return $arr;
      break;
  }
}

function userDeets($userID) {
  $i = 0;
  $type = "user";
  foreach (curlyAction($type,$userID) as $value) {
  	echo strtoupper($value["name"]);
  	echo "\r\n";
    echo "Rank: ".$value["rank"];
  	echo "\r\n";
    echo "Total Points: ".$value["totalPoints"];
  	echo "\r\n";
  	echo "Current Region: ".$value["region"]["name"];
  	echo "\r\n";
  	echo "Points Per Hour: ".$value["pointsPerHour"];
  	echo "\r\n";
  	echo "Unique Zones Taken: ".$value["uniqueZonesTaken"];
  	echo "\r\n";
  	echo "Current Zones held: ";
   	foreach ($value["zones"] as $zoneID) {
      $i++;
      zoneDeets($zoneID,$i);
	  }
  }
}

function zoneDeets($zoneID,$count) {
  $type = "zone";
  foreach (curlyAction($type,$zoneID) as $zone) {
	  $your_date = strtotime($zone["dateLastTaken"]);
    $now = time(); // or your date as well
    $datediff = $now - $your_date;
  	echo "\r\n".$count.". ".$zone["name"]."(".$zone["region"]["name"].",".round($datediff / (60 * 60 * 24))." days)";
		}
}

$userID = "tbone";

userDeets($userID);




?>
