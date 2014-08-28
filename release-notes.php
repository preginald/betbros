<?php

//print_r_pre($_POST);
$url = curPageURLshort();
$version = version();

if(isset($_POST['add_note'])){
	$vID = $_POST['vID'];
	$rnote = $_POST['rnote'];
	$rdetails = $_POST['rdetails'];
	$createTimestamp = date("Y-m-d H:i:s");
	
	if(!empty($_POST['vnote'])){
	
		$vnumber = $_POST['vnumber'];
		$vnote = $_POST['vnote'];
		$vdetails = $_POST['vdetails'];
	
		echo $sql1 = "INSERT INTO `version` (`number`, `note`, `details`, `createUserID`, `createTimestamp`)
		VALUES ($vnumber, '$vnote', '$vdetails', '$session_user_id', '$createTimestamp')";
		$result = mysql_query($sql1);
		if($result){
			$vID = mysql_insert_id();
		} else {
			echo "ERROR: add new version";
		}
	
	}
	
	$sql2 = "INSERT INTO `release` (`versionID`,`note`,`details`, `createUserID`, `createTimestamp`)
	VALUES ('$vID', '$rnote', '$rdetails', '$session_user_id', '$createTimestamp')";
	$result = mysql_query($sql2);
	if($result){
		//echo "successfully added release note";
	} else {
		echo "ERROR: add new release note";
	}

}

if($session_user_id==1){
	if (!isset($_GET["addversion"])) {
		$URL = $url . "&addversion=1";
		$vlink = '<a href="' . $URL  . '">Add Version</a>';
	 } elseif (isset($_GET["addversion"]) && $_GET["addversion"] == 0) {
		$URL = str_replace("&addversion=0","&addversion=1",$url);
		$vlink = '<a href="' . $URL  . '">Add Version</a>';
	}else{
		$URL = str_replace("&addversion=1","&addversion=0",$url);
		$vlink = '<a href="' . $URL  . '">Hide Version</a>';
	}
}

if($session_user_id==1){
	if (!isset($_GET["addnote"])) {
		$URL = $url . "&addnote=1";
		echo '<a href="' . $URL  . '">Show Add Note</a>';
	 } elseif (isset($_GET["addnote"]) && $_GET["addnote"] == 0) {
		$URL = str_replace("&addnote=0","&addnote=1",$url);
		echo '<a href="' . $URL  . '">Show Add Note</a>';
	}else{
		$URL = str_replace("&addnote=1","&addnote=0",$url);
		echo '<a href="' . $URL  . '">Hide Add Note</a>';
	}
}


if(isset($_GET['rdetail'])){
	$rnID = $_GET['rdetail'];
	$rndetail = get_rdetail($rnID);
	$rndetail = '<div class="w400 p5 bbl">'.$rndetail.'</div>';
}

if(isset($_GET['addnote']) && $_GET['addnote']=="1"){
	echo '<form method="post" action="">';
	echo '<br/>';
	echo 'Current version: 0.1.' . $version . ' ';
	echo $vlink;
	if(isset($_GET['addversion']) && $_GET['addversion']=="1"){include 'version.php';}
	echo '<br/>';

	echo '<input type="text" name="rnote" size="60" required placeholder="Note summary">';

	echo '<br/>';
	echo '<textarea rows="4" cols="55" name="rdetails" placeholder="Details"></textarea>';
	echo '<br/>';
	echo '<input type="hidden" name="vID" value="'.version().'">';
	echo '<input type="submit" value="Submit" name="add_note">';
	echo '</form>';

}

if(isset($_GET['view']) && $_GET['view'] =="default"){
echo "<h1>Release Notes</h2>";

	$sql = "SELECT v.number vID, createTimestamp from version v ORDER BY ID DESC";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){
	
		$vID = $row['vID'];
		$vDate = nice_date($row['createTimestamp'],"d M h:m a");
		
		echo "<h2>0.1.$vID / $vDate </h2>";
	
		$sql2 = "SELECT `ID`, `note`, `createTimestamp` from `release` WHERE versionID = $vID ORDER BY ID ASC";
		$result2 = mysql_query($sql2);
		
		echo "<ul>";
		while($row2 = mysql_fetch_assoc($result2)){
		
			$rlID =  $row2['ID'];
			$note =  $row2['note'];
			$date = $row2['createTimestamp'];
			//$date = date("D M",strtotime($date));
			$date = '<span class="f10">' .nice_date($date,"d M h:m a") . '</span>';
			
			
			$output = "<li>";
			$output .= "- ";
			//$output .= $rlID;
			//$output .= "/";
			$output .= '<a href="'. URL_replace_long($url,"&rdetail=","&rdetail=$rlID") .'">';
			$output .= (isset($_GET['rdetail']) && $_GET['rdetail'] == $rlID) ? '<span class="tb">'.$note.'</span>' : $note;
			$output .= '</a>';
			$output .= ' ' . $date;
			$output .= (isset($_GET['rdetail']) && $_GET['rdetail'] == $rlID) ? $rndetail : '';
			$output .= "</li>";
			
			echo $output;
		
		}
	
	}	echo "</ul>";
	echo "<br/>";
}

?>