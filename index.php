<?php
// 3-10-2012 anna billstrom, facebook test platform
// facebook test platform
?>
<html>
<style type="text/css">
body {
	padding:4px;
	margin:4px;
	font-family:arial;
	font-size:10px;
}
.error td, .warning td {
	margin:4px;
	padding:4px;
		background-color:red;
		color:white;
		border:1px solid grey;
		width:200px;
	}
.warning td{
	background-color:yellow;
	color:black;
	
}
</style>
<?php


//require('/var/www/html/tools/dBug.php');
// me test

$return_object = new stdClass();
$uid = 100;

if(isset($_REQUEST['me'])){
	returnUser();
}
if(isset($_REQUEST['method'])){
	if($_REQUEST['method'] == 'feed'){
		returnFeedPost($_REQUEST);
	}
}

if(isset($return_object->error)){
	echo "<div class=error>";
	echo "<h2>errors</h2>";
	echo "<table>";
	foreach($return_object->error as $type=>$err){
		foreach ($err as $value){
			echo "<tr><td><strong>".$type ."</strong></td><td> " .$value."</td></tr>";			
		}
	}
	echo "</table></div>";
}

if(isset($return_object->warning)){
	echo "<div class=warning>";
	echo "<h2>warnings</h2>";
	echo "<table>";
	foreach($return_object->warning as $type=>$warn){
		foreach ($warn as $value){
			echo "<tr><td><strong>".$type ."</strong></td><td> " .$value."</td></tr>";			
		}
	}
	echo "</table>";
	echo "</div>";
}

$return_str = json_encode($return_object);
echo $return_str;


function returnUser(){
	global $return_object;
	$return_object->uid = $uid;
	$return_object->name = "Anna Test";
	$return_object->first_name = "Anna";
	$return_object->last_name = "Test";
	$return_object->link = "http://www.facebook.com/profile.php?id=".$uid;
	$return_object->quotes = "To be, or not to be.";
	$return_object->gender = "female";
	$return_object->timezone = -8;
	$return_object->locale = "en_US";
	$return_object->verified=true;
	$return_object->updated_time = date()-10;
	$return_object->type = "user";
	return $return_object;
}

// test publish story
function returnFeedPost($_REQUEST){
	global $return_object;

	if(!isset($_REQUEST['link'])){
		$return_object->error['picture'][] = "No link provided.";
	}
	if(isset($_REQUEST['picture'])){
		// check not https
		if(isset($_REQUEST['picture'])){
			$matches = preg_match("https:",$_REQUEST['picture']);
			if(count($matches > 0)){
				$return_object->error['picture'][] = "Facebook requires http:// picture urls, not https";
			}
		} else {
				$return_object->error['picture'][] = "No picture for feed story";
		}
		
	}
	if(!isset($_REQUEST['message'])){
			$return_object->error['message'][] = "No message - no text or story- for feed story";
	}
	if(!isset($_REQUEST['name'])){
		$return_object->error['name'][] = "No name.";		
	}
	if(!isset($_REQUEST['caption'])){
		$return_object->warning['caption'][] = "No caption.";		
	}
	if(!isset($_REQUEST['properties'])){
		$return_object->warning['properties'][] = "No properties - is that OK?";
	}
	if(!isset($_REQUEST['actions'])){
		$return_object->warning['action_links'][] = "No actions - is that OK?";
	}
	$return_object->message = $_REQUEST['message'];
	$return_object->picture = $_REQUEST['picture'];
	return $return_object;
}
?>