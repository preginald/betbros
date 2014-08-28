<?php include 'core/init.php'; 
$option = $_GET['option'];
$json = array();

if ($option == "update") {
	$userid = $_GET['userid'];
	$firstname = $_GET['firstname'];
	$lastname = $_GET['lastname'];
	$email = $_GET['email'];
	$tz = $_GET['tz'];

	$s = "UPDATE `users` SET `first_name`='$firstname',`last_name`='$lastname',`email`='$email',`zone_id`=$tz WHERE `user_id`=$userid";
	
	$r = mysql_query($s);
	if ($r) {
		$json['status'] = "success";	
	} else {
		$json['status'] = "error";
		$json['s'] = $s;
	}

	echo json_encode($json);

}