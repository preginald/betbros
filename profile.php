<?php
$url = curPageURLshort();

$profilefarray = array( "bk" => "Bookies","labels" => "Labels");
$last = end($profilefarray);

foreach ($profilefarray as $key => $value) {

	if (!isset($_GET[$key])) {
		$URL = $url . "&$key=1";
		echo '<a href="' . $URL  . '">Show ' . $value . '</a>';
		echo ($value == $last) ? " " : " | ";
	 	
	 } elseif (isset($_GET[$key]) && $_GET[$key] == 0) {
		$URL = str_replace("&$key=0","&$key=1",$url);
		echo '<a href="' . $URL  . '">Show ' . $value . '</a>';
		echo ($value == $last) ? " " : " | ";
	}else{
		$URL = str_replace("&$key=1","&$key=0",$url);
		echo '<a href="' . $URL  . '">Hide ' . $value . '</a>';
		echo ($value == $last) ? " " : " | ";
	}
}



 if($_GET['username'] != $user_data['username']) { // this checks to make sure username value in url equals to username from session

 	echo 'User profile error';

 } else {

 	if (isset($_GET['username']) === true && empty($_GET['username']) === false) {

 		$username 		= $_GET['username'];

 		if (user_exists($username) === true) {

 			?>
 			<div class="alert" role="alert"></div>
 			<div class="col-md-5" id="profile" data-username="<?= $username ?>"></div>
 			<div class="col-md-5" id="userbank"></div>
 			<div class="col-md-5" id="userbookies"></div>
 			<div class="col-md-7" id="labels"></div>
 			<?php


 		} else {

 			echo 'Sorry, that user doesn\'t exist!';

 		}

 	} else {

 		header('Location: index.php');

 		exit();

 	}

 }
?>
<script type="text/javascript" src="js/profile.js"></script>