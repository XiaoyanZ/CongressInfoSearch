<?php
header("charset=utf-8");
define("APIKEY", _YOURAPIKEY,true);
define("APIURL", _APIURL,true);
//define valid actions
$valid_action = array(
	"loadAllLegislators",
	"loadAllBills",
	"loadAllCommittees",
	"loadLegisDetails",
	"loadBillDetails",
    "loadHouseLegis",
	"loadSenateLegis",
    "loadActiveBills",
    "loadNewBills",
    "loadHouseComm",
    "loadSenateComm",
    "loadJointComm",
    "loadCommDetails"
);

//check action
if(!isset($_GET["action"])){
	die("missing param");
}
$action = $_GET["action"];
if(!in_array($action, $valid_action)){
	die("invalid action");
}
$json = null;

//corresponding handlers to different actions
if($action == $valid_action[0]){
    if(isset($_GET["state_name"])){
        $url = APIURL."/legislators?per_page=all&state_name=".$_GET["state_name"]."&apikey=".APIKEY;
    }else{
        $url = APIURL."/legislators?per_page=all&apikey=".APIKEY;
    }
	$json = getData($url);
}
else if($action == $valid_action[1]){
	$url = APIURL."/bills?per_page=50&history.active=true&last_version.urls.pdf__exists=true&apikey=".APIKEY;
	$json1 = getData($url);
	$url = APIURL."/bills?per_page=50&history.active=false&last_version.urls.pdf__exists=true&apikey=".APIKEY;
	$json2 = getData($url);
	$json = json_encode(array_merge(json_decode($json1,true)["results"],json_decode($json2,true)["results"]));

}
else if($action == $valid_action[2]){
	$url = APIURL."/committees?per_page=all&apikey=".APIKEY;
	$json = getData($url);
}
else if($action == $valid_action[3]){
	if(isset($_GET["bioguide_id"])){
		$url = APIURL."/legislators?bioguide_id=".$_GET["bioguide_id"]."&apikey=".APIKEY;
		$personal_info = getData($url);
		$url = APIURL."/committees?member_ids=".$_GET["bioguide_id"]."&apikey=".APIKEY;
		$committee_info = getData($url);
		$url = APIURL."/bills?sponsor_id=".$_GET["bioguide_id"]."&apikey=".APIKEY;
		$bill_info = getData($url);
		$json = json_encode(array("personal_info" => json_decode($personal_info), "committees_info" => json_decode($committee_info), "bills_info" => json_decode($bill_info)));
	}
}
else if($action == $valid_action[4]){
	if(isset($_GET["bill_id"])){
		$url = APIURL."/bills?bill_id=".$_GET["bill_id"]."&apikey=".APIKEY;
		$json = getData($url);
	}
}
else if($action == $valid_action[5]){
	$url = APIURL."/legislators?per_page=all&chamber=house&apikey=".APIKEY;
	$json = getData($url);
}
else if($action == $valid_action[6]){
	$url = APIURL."/legislators?per_page=all&chamber=senate&apikey=".APIKEY;
	$json = getData($url);
}
else if($action == $valid_action[7]){
	$url = APIURL."/bills?per_page=50&history.active=true&last_version.urls.pdf__exists=true&apikey=".APIKEY;
	$json = getData($url);
}
else if($action == $valid_action[8]){
	$url = APIURL."/bills?per_page=50&history.active=false&last_version.urls.pdf__exists=true&apikey=".APIKEY;
	$json = getData($url);
}
else if($action == $valid_action[9]){
	$url = APIURL."/committees?per_page=all&chamber=house&apikey=".APIKEY;
	$json = getData($url);
}
else if($action == $valid_action[10]){
	$url = APIURL."/committees?per_page=all&chamber=senate&apikey=".APIKEY;
	$json = getData($url);
}
else if($action == $valid_action[11]){
	$url = APIURL."/committees?per_page=all&chamber=joint&apikey=".APIKEY;
	$json = getData($url);
}
else if($action == $valid_action[12]){
	if(isset($_GET["committee_id"])){
		$url = APIURL."/committees?committee_id=".$_GET["committee_id"]."&apikey=".APIKEY;
		$json = getData($url);
	}
}


echo $json;

//retrieve data from API
function getData($url){
	$json = file_get_contents($url);
	return $json;
}
?>
