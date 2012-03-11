<?php
// 3-10-2012 anna billstrom, facebook test platform
// facebook test platform
?>
<html>
<style type="text/css">
.error, h2 {
	background-color:red;
	color:white;
	border:1px solid grey;
	width:200px;
	padding:4px;
	margin:4px;
	font-family:arial;
	font-size:10px;
	}
</style>
<?php


require('/var/www/html/tools/dBug.php');
// me test
new dBug($_REQUEST);
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
new dBug($return_object);
if(isset($return_object->error)){
	echo "<h2>errors</h2>";
	foreach($return_object->error as $type=>$err){
		echo "<div class=error>";
		foreach ($err as $value){
			echo $type .": " .$value;			
		}
		echo "</div>";
	}
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
	$return_object->message = $_REQUEST['message'];
	$return_object->picture = $_REQUEST['picture'];
	return $return_object;
}
?>