<?php
protect_page(); 

$sportsID = mysql_real_escape_string($_GET['sid']);
$eventSeasonID = mysql_real_escape_string($_GET['esid']);
$fixtureID = mysql_real_escape_string($_GET['fixid']);
$htid = mysql_real_escape_string($_GET['htid']);
$atid = mysql_real_escape_string($_GET['atid']);
$fixid=$fixtureID;

if((isset($_GET['page']) && $_GET['page']=="shot") && isset($_POST['add_shot'])){
	
  include 'add_shot';
}

if(isset($_GET['refreshscore'])){
	$htscore=total_home_goals($fixtureID,$htid,$atid);
	$atscore=total_away_goals($fixtureID,$htid,$atid);
	$createTimestamp = date("Y-m-d H:i:s");
	echo $sql_update_score=
	"UPDATE `fixtureResults` SET `htscore`='$htscore',`atscore`='$atscore',`modifyUserID`='$session_user_id',`modifyTimestamp`='$createTimestamp' WHERE `fixtureID` = '$fixtureID'";
	$result=mysql_query($sql_update_score);

	if($result){
		echo "successfully updated score";
	} else {
		echo "error";
	}
}

// start display page=shot&view=add
if((isset($_GET['page']) && $_GET['page']=="shot") && (isset($_GET['view']) && $_GET['view']=="add")
	||
	(isset($_GET['page']) && $_GET['page']=="fixtures") && (isset($_GET['addshot']) && $_GET['addshot']=="1")){

	//include 'score.php';

	// display dynamic line up heading
	echo "<h1>Add shot</h1>";

	// display form to add shot
	?>


	<table width="100%">
		<thead>
		<tr>
		<th width="50%"><?php echo fixture_home_heading($fixid) ?></th>
		<th width="50%"><?php echo fixture_away_heading($fixid) ?></th>
		</tr>
		</thead>
		<tr>
		<td>	
		<form name="add-shot-home" method="post" action="" >
		
		<div>
		<select name="periodID" required><?php period_dropDown($sportsID) ?></select>
		
		<span class="small">Minute</span>
		<select name="minute" required><?php minute_dropDown() ?></select>	

		<span class="small">Second</span>
		<select name="second" required><?php second_dropDown() ?></select>

		<select name="incidentID" required><?php incident_dropDown($sportsID) ?></select>

		<select name="teamPlayerID" required><?php home_team_player_from_lineup_dropDown($fixid,$htid) ?></select>

		<select name="bodyPartID" required><?php bodyPart_dropDown() ?></select>

		<select name="fromID" required><?php from_dropDown() ?></select>

		<input type="submit" name="add_shot" value="Add">
		</form>
		</div>
		</td>

		<td>
		<form name="add-shot-away" method="post" action="<?=$PHP_SELF?>" >

		<div>
		<select name="periodID" required><?php period_dropDown($sportsID) ?></select>

		<span class="small">Minute</span>
		<select name="minute" required><?php minute_dropDown() ?></select>	

		<span class="small">Second</span>
		<select name="second" required><?php second_dropDown() ?></select>

		<select name="incidentID" required><?php incident_dropDown($sportsID) ?></select>

		<select name="teamPlayerID" required><?php away_team_player_from_lineup_dropDown($fixid,$atid) ?></select>

		<select name="bodyPartID" required><?php bodyPart_dropDown() ?></select>

		<select name="fromID" required><?php from_dropDown() ?></select>

		<input type="submit" name="add_shot" value="Add">
		</form>
		</div>
		</td>
		</tr>

		
		<?php 
		?>
	</table>

	<?php 
}

//echo "<h2>Shots " . '<a href="index.php?page=shot&view=add&sid='. "$sportsID". '&esid='. "$eventSeasonID". '&fixid='. "$fixtureID". fixture_home_away_url($fixid) . '&matchtime=add"><img alt="add" style="width: 24px; height: 24px;" title="add" src="images/add-record.png"></a></h2>';
echo "<h2>Shots</h2>";

	$sql=
	"SELECT
	sht.ID AS shid,
	prd.name AS prdname,
	sht.time AS stime,

	i.name AS iname,

	p.firstName AS pfname,
	p.lastName AS plname,

	tp.teamSeasonID AS tsid,
	t.name AS tname,

	bp.name AS bpname,

	f.name AS fname

	FROM shot AS sht

	INNER JOIN period AS prd
	ON sht.periodID=prd.ID

	INNER JOIN incident AS i
	ON sht.incidentID=i.ID

	INNER JOIN teamPlayers AS tp
	ON sht.teamPlayerID=tp.ID

	INNER JOIN teamSeason AS ts
	ON tp.teamSeasonID=ts.ID

	INNER JOIN teams AS t
	ON ts.teamID=t.ID

	INNER JOIN person AS p
	ON tp.personID=p.ID

	INNER JOIN bodyPart AS bp
	ON sht.bodyPartID=bp.ID

	INNER JOIN `from` AS f
	ON sht.fromID=f.ID

	WHERE sht.fixtureID=$fixtureID
	ORDER BY prd.orderBy,sht.time ASC ";

	$result = mysql_query($sql);

	?>

	<table class="bordered">
	<thead>
	<tr>
	<th>ID</th>
	<th>Half</th>
	<th>Time</th>
	<th></th>
	<th></th>
	</tr>
	</thead>

	<?php
	while($row=mysql_fetch_assoc($result)){
		?>
		<tr>
		<td><?php echo $row['shid'] ?></td>
		<td><?php echo $row['prdname'] ?></td>
		<td><?php echo $row['stime'] ?></td>
		<td><?php echo "<strong>" . $row['iname'] ."</strong>" ." ". $row['pfname'] ." ". $row['plname'] ." (".$row['tname'] .") with ". $row['bpname'] ." from ". $row['fname'] ?></td>
		<td>update delete</td>
		<?php
	}

	echo "</table>";
if(isset($_GET['page']) && $_GET['page']=="shot" && isset($_GET['view']) && $_GET['view']=="add"){
	include 'matchtime.php';
}
?>