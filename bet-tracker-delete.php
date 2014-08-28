<?php
if(isset($_POST['task']) && $_POST['task'] == 'delete'){
	require_once 'core/database/connect.php';
	$btID = $_POST['btID'];

	//get all betDetail records related to this btID
	mysql_query("DELETE FROM `betDetail` WHERE betTrackerID = $btID");
	mysql_query("DELETE FROM `betTracker` WHERE ID = $btID");
}