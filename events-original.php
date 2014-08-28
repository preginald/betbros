<?php 

$curPage=curPageURLshort();

// get values from url
$sportsID = mysql_real_escape_string($_GET['sid']); 
$activeID = mysql_real_escape_string($_GET['active']); 

if (isset($_GET['esid'])) {
	$eventSeasonID = mysql_real_escape_string($_GET['esid']);
}


$this_week_start = date('Y-m-d', strtotime('this week')); 
$this_week_finish = date('Y-m-d', strtotime('next week'));

// start add eventSeason to database process
// check if form button = submit 

if(isset($_POST['add_eventSeason'])){
	$ename = $_POST['ename'];
	$cID = $_POST['cID'];

	$eventID = $_POST['eventID'];
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];
	$sponsorID = $_POST['sponsorID'];
	$sponsor = $_POST['sponsor'];

	$createTimestamp = date("Y-m-d H:i:s");

	$startY = date("Y",strtotime($startDate));
	$endY = date("Y",strtotime($endDate));

	if(!empty($ename)){
		$result=mysql_query(
			"INSERT INTO `events`(
				`name`, `countryID`, `sportID`,`verified`, `createUserID`, `createTimestamp`) 
			VALUES (
				'$ename',$cID,$sportsID,0,'$session_user_id','$createTimestamp')"
		);


		if($result){
			$eventID=mysql_insert_id();
			// echo "successfully added event name";
		} else {
			echo "ERROR: Insert event name error";
		}
	}

	if(!empty($sponsor)){
		$result=mysql_query(
			"INSERT INTO `brands`(
				`name`, `verified`, `createUserID`, `createTimestamp`) 
			VALUES (
				'$sponsor',0,'$session_user_id','$createTimestamp')"
		);

		if($result){
			$sponsorID=mysql_insert_id();
			// echo "successfully added event name";
		} else {

			echo "ERROR: Insert event name error";
		}
	}

	// check if event already exists
	$sql_check_event= "SELECT * FROM `eventSeason` WHERE `eventsID` = $eventID
	AND YEAR(`startDate`) = $startY
	AND YEAR(`endDate`) = $endY";
	$sql_check_event;
	$result=mysql_query($sql_check_event);
	if (mysql_fetch_row($result)) {

		echo "This event already exists";

	} else {

		$sql_add_event = 
		"INSERT INTO `eventSeason`
				(`eventsID`, `startDate`, `endDate`, `sponsorID`, `verified`,`createUserID`, `createTimestamp`) 
		VALUES  ('$eventID','$startDate','$endDate','$sponsorID',0,'$session_user_id','$createTimestamp')";

		$result=mysql_query($sql_add_event);

		// end add event to database process
		// if successfully updated. 

		if($result){

			echo "successfully added event";

		} else {

				echo "ERROR";

		}
	}
}
	// end add event process

	if($activeID==1){
	$where="WHERE s.ID = '$sportsID' AND es.startDate <= CURDATE() AND es.endDate >= CURDATE()";
	$headingActive="Current";
	}
	if($activeID==2){
	$where="WHERE s.ID = '$sportsID'";
	$headingActive="All";
	}

	// query to get list of sports names for heading
	$sql_heading = "SELECT * FROM `sports` WHERE 1";
	$result_heading = mysql_query($sql_heading);

	if(isset($_GET['addevent']) && $_GET['addevent']=="1"){ ?>
	<h1>Add new event</h1>

	<form name="add-eventSeason" method="post" action="" >

	<div>
	<h3>Step 1: Select event name</h3>
	<span class="small">Event Name</span>
	<select name="eventID"><?php event_dropDown($eventID,$sportsID) ?></select>
	</div>

	<br/>

	<div>
	<h3>Step 2: Or add a new event name</h3>
	<input type="text" name="ename" placeholder="Event Name">

	<select name="cID"><?php country_dropDown() ?></select>
	</div>

	<br/>

	<h3>Step 3: Add other event info</h3>
	<div>
	<span class="small">Event starting date</span>
	<input type="date" name="startDate" placeholder="Start Date">
	</div>

	<div>
	<span class="small">Event ending date</span>
	<input type="date" name="endDate" placeholder="End Date">
	</div>

	<h3>Step 3: Select event sponsor</h3>
	<div>
	<span class="small">Event sponsor</span>
	<select name="sponsorID"><?php brands_dropDown() ?></select> Or Add new sponsor
	<input type="text" name="sponsor" placeholder="Event Sponsor">
	</div>

	<input type="submit" name="add_eventSeason" value="Add">
	</form>
	<?php 
} 


if(isset($_GET['view']) && $_GET['view']=="list"){

	while($row_heading = mysql_fetch_assoc($result_heading)) {

		$ID = $row_heading['ID'];
		$name = $row_heading['name'];
		$page = "events-$ID.php";

		if(isset($_GET['sid']) && $_GET['sid']==$ID) {

			echo "<h1>$headingActive $name Events " . '<a href="' . $curPage  .'&addevent=1"><img alt="add" style="width: 24px; height: 24px;" title="add" src="images/add-record.png"></a></h1>';
			
			$sql = "SELECT DISTINCT

			c.name AS cname,
            c.id AS cid
            
			FROM eventSeason AS es
			INNER JOIN events AS e
			ON es.eventsID=e.ID
			INNER JOIN brands AS spn
			ON es.sponsorID=spn.ID
			INNER JOIN countries AS c
			ON e.countryID=c.id
			INNER JOIN sports AS s
			ON e.sportID=s.ID
            
            WHERE s.ID=2
            
            ORDER BY c.name";

            $result = mysql_query($sql);

            while($row = mysql_fetch_array($result)) {
            	$cname = $row['cname'];
            	$cID = $row['cid'];
            	echo '<b>' . $cname . '</b>';

            	$sql1 = "SELECT 
				es.ID AS ID, 
				es.ID AS esID,
				es.eventsID AS eid,
				e.name AS ename, 
				spn.name AS spnname, 
				es.startDate AS sdate, 
				es.endDate AS edate, 
				c.name AS cname, 
				c.alpha_2 AS calpha, 
				s.ID AS sid
				FROM eventSeason AS es
				INNER JOIN events AS e
				ON es.eventsID=e.ID
				INNER JOIN brands AS spn
				ON es.sponsorID=spn.ID
				INNER JOIN countries AS c
				ON e.countryID=c.id
				INNER JOIN sports AS s
				ON e.sportID=s.ID
				$where AND c.id = $cID

				ORDER BY spn.name";

				$result1 = mysql_query($sql1);

			while($row = mysql_fetch_array($result1)) {
				$esID = $row['esID'];
				echo '<div><a href="index.php?page=events&view=detail&sid=' .$sportsID . '&esid='. $row['ID'] . '&teamslist=1&fixlist=1&filter=default">' . eventSeasonHeading_v2($esID) . '</a></div>';
			}
			echo '<br/>';

            }

		}

	}
}


if(isset($_GET['view']) && $_GET['view']=="detail"){
	
	// display page heading
	?>
	<h1><?= eventSeasonHeading_v2($eventSeasonID) ?></h1>
	<?php


	// display teams table
	// echo "<h2>Teams</h2>";
	include 'teams.php';

	include 'fixtures.php';

	// display personnel and kits
	//echo "<h2>Personnel and kits</h2>";

	// display league table
	//echo "<h2>League table</h2>";

	// display results
}
