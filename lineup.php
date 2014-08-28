<?php
protect_page(); 

$sportsID = mysql_real_escape_string($_GET['sid']);
$eventSeasonID = mysql_real_escape_string($_GET['esid']);
$fixtureID = mysql_real_escape_string($_GET['fixid']);
$htid = mysql_real_escape_string($_GET['htid']);
$atid = mysql_real_escape_string($_GET['atid']);
if (isset($_GET['lineupStatusID'])) {
	$lineupStatusID = mysql_real_escape_string($_GET['lineupStatusID']);
}

if(isset($_GET['page']) && $_GET['page']=="lineup" && isset($_POST['add_lineup'])){
	include 'add_lineup';
}


if(isset($_GET['page']) && $_GET['page']=="fixtures" && isset($_GET['view']) && $_GET['view']=="detail"){

	//echo "<h2>Line-up " . '<a href="index.php?page=lineup&view=add&sid='. "$sportsID". '&esid='. "$eventSeasonID". '&fixid='. "$fixtureID". fixture_home_away_url($fixID) . '"><img alt="add" style="width: 24px; height: 24px;" title="add" src="images/add-record.png"></a></h2>';
	echo "<h2>Line-up</h2>";

}


// start display page=lineup&view=add
if((isset($_GET['page']) && $_GET['page']=="lineup" && isset($_GET['view']) && $_GET['view']=="add") ||
	(isset($_GET['page']) && $_GET['page']=="fixtures" && isset($_GET['addlineup']) && $_GET['addlineup']=="1")){
	
	$htid = mysql_real_escape_string($_GET['htid']);
	$atid = mysql_real_escape_string($_GET['atid']);

	// include 'score.php';
	// display dynamic line up heading

	// display form to add player to lineup ?>
	

	<table width="100%">
	<thead>
	<tr>
	<th width="50%"><?php echo fixture_home_heading($fixtureID) ?></th>
	<th width="50%"><?php echo fixture_away_heading($fixtureID) ?></th>
	</tr>
	</thead>
	<tr>
	<td>	
	<form name="add-lineup-home" method="post" action="<?=$PHP_SELF?>" >
	<div>
	<span class="small"><a href="index.php?page=players&view=add&sid=<?php echo $_GET['sid'] ?>&tsid=<?php echo $_GET['htid'] ?>" target="_blank">Home Player</a></span>
	<select name="teamPlayerID" ><?php home_team_season_dropDown($htid) ?></select>

	<span class="small">Lineup</span>
	<select name="lineupStatusID" required><?php lineup_status_dropDown() ?></select>

	<h3>Or add a new player:</h3>
	<div>

	<select name="pID"><?php person_dropDown() ?></select>

	<br/>
	Or add new person
	<select name="sex"><?php person_gender_dropdown() ?></select>
	<input type="text" name="fname" placeholder="First Name" >
	<input type="text" name="mname" placeholder="Middle Name">
	<input type="text" name="lname" placeholder="Last Name" ><br/>
	<input type="date" name="dob" placeholder="Date of birth">
	<input type="number" name="height" placeholder="Height">
	<select name="cID" ><?php country_dropDown() ?></select>
	<h3>Step 4: Player's team info:</h3>
	<input type="number" name="number" placeholder="Team Player Number" >
	<select name="positionCatID" ><?php position_cat_dropDown() ?></select>
	</div>

	<input type="hidden" name="teamSeasonID" value="<?php echo $_GET['htid'] ?>">
	<input type="submit" name="add_lineup" value="Add">
	</form>
	</div>
	</td>

	<td>
	<form name="add-lineup-away" method="post" action="<?=$PHP_SELF?>" >
	<div>
	<span class="small"><a href="index.php?page=players&view=add&sid=<?php echo $_GET['sid'] ?>&tsid=<?php echo $_GET['atid'] ?>" target="_blank">Away Player</a></span>
	<select name="teamPlayerID" ><?php away_team_season_dropDown($atid) ?></select>

	<span class="small">Lineup</span>
	<select name="lineupStatusID" required><?php lineup_status_dropDown() ?></select>

	<h3>Or add a new player:</h3>
	<div>

	<select name="pID"><?php person_dropDown() ?></select>

	<br/>
	Or add new person
	<select name="sex"><?php person_gender_dropdown() ?></select>
	<input type="text" name="fname" placeholder="First Name" >
	<input type="text" name="mname" placeholder="Middle Name">
	<input type="text" name="lname" placeholder="Last Name" ><br/>
	<input type="date" name="dob" placeholder="Date of birth">
	<input type="number" name="height" placeholder="Height">
	<select name="cID" ><?php country_dropDown() ?></select>
	<h3>Step 4: Player's team info:</h3>
	<input type="number" name="number" placeholder="Team Player Number" >
	<select name="positionCatID" ><?php position_cat_dropDown() ?></select>
	</div>

	<input type="hidden" name="teamSeasonID" value="<?php echo $_GET['atid'] ?>">
	<input type="submit" name="add_lineup" value="Add">
	</form>
	</div>
	</td>
	</tr>

	<tr>
	<td valign="top">
				<?php 
				$sql=
				"SELECT
				fixl.ID AS ID,
				fixl.fixtureID AS fixid,
				fixl.teamPlayerID AS pid,

				p.firstName AS pfname,
				p.lastName AS plname,

				fixl.lineupStatusID AS lsid,
				ls.name AS lsname,

				tp.number AS num,
				tp.teamSeasonID AS tsid

				FROM fixtureLineup AS fixl

				INNER JOIN lineupStatus AS ls
				ON fixl.lineupStatusID=ls.ID

				INNER JOIN teamPlayers AS tp
				ON fixl.teamPlayerID=tp.ID

				INNER JOIN person AS p
				ON tp.personID=p.ID

				WHERE fixl.fixtureID=$fixtureID 
				AND tp.teamSeasonID=$htid
				AND fixl.lineupStatusID=1
				";

				$result = mysql_query($sql);

				?>

				<table class="bordered" width="100%">
				<thead>
				<tr>
				<th>ID</th>
				<th>Starting</th>
				</tr>
				</thead>

				<?php
				while($row = mysql_fetch_assoc($result)) {
					?>
					<tr>
				  <td><?php echo $row['ID'] ?></td>
				  <td><?php echo $row['num'] .". " . $row['pfname'] ." ". $row['plname'] ?></td>
				  <td>update  delete</td>
				  </tr>
				  <?php 


				}
				echo "</table>"; ?>

				<?php 
				$sql=
				"SELECT
				fixl.ID AS ID,
				fixl.fixtureID AS fixid,
				fixl.teamPlayerID AS pid,

				p.firstName AS pfname,
				p.lastName AS plname,

				fixl.lineupStatusID AS lsid,
				ls.name AS lsname,

				tp.number AS num,
				tp.teamSeasonID AS tsid

				FROM fixtureLineup AS fixl

				INNER JOIN lineupStatus AS ls
				ON fixl.lineupStatusID=ls.ID

				INNER JOIN teamPlayers AS tp
				ON fixl.teamPlayerID=tp.ID

				INNER JOIN person AS p
				ON tp.personID=p.ID

				WHERE fixl.fixtureID=$fixtureID 
				AND tp.teamSeasonID=$htid
				AND fixl.lineupStatusID=2
				";

				$result = mysql_query($sql);

				?>

				<table class="bordered" width="100%">
				<thead>
				<tr>
				<th>ID</th>
				<th>Substitute</th>
				</tr>
				</thead>

				<?php
				while($row = mysql_fetch_assoc($result)) {
					?>
					<tr>
				  <td><?php echo $row['ID'] ?></td>
				  <td><?php echo $row['num'] .". " . $row['pfname'] ." ". $row['plname'] ?></td>
				  <td>update  delete</td>
				  </tr>
				  <?php 


				}
				echo "</table>"; ?>
	</td>

	<td valign="top">
				<?php 
				$sql=
				"SELECT
				fixl.ID AS ID,
				fixl.fixtureID AS fixid,
				fixl.teamPlayerID AS pid,

				p.firstName AS pfname,
				p.lastName AS plname,

				fixl.lineupStatusID AS lsid,
				ls.name AS lsname,

				tp.number AS num,
				tp.teamSeasonID AS tsid

				FROM fixtureLineup AS fixl

				INNER JOIN lineupStatus AS ls
				ON fixl.lineupStatusID=ls.ID

				INNER JOIN teamPlayers AS tp
				ON fixl.teamPlayerID=tp.ID

				INNER JOIN person AS p
				ON tp.personID=p.ID

				WHERE fixl.fixtureID=$fixtureID 
				AND tp.teamSeasonID=$atid
				AND fixl.lineupStatusID=1";

				$result = mysql_query($sql);

				?>

				<table class="bordered" width="100%">
				<thead>
				<tr>
				<th>ID</th>
				<th>Starting</th>
				</tr>
				</thead>

				<?php
				while($row = mysql_fetch_assoc($result)) {
					?>
					<tr>
				  <td><?php echo $row['ID'] ?></td>
				  <td><?php echo $row['num'] .". " . $row['pfname'] ." ". $row['plname'] ?></td>
				  <td>update  delete</td>
				  </tr>
				  <?php 


				}
				echo "</table>"; ?>

				<?php 
				$sql=
				"SELECT
				fixl.ID AS ID,
				fixl.fixtureID AS fixid,
				fixl.teamPlayerID AS pid,

				p.firstName AS pfname,
				p.lastName AS plname,

				fixl.lineupStatusID AS lsid,
				ls.name AS lsname,

				tp.number AS num,
				tp.teamSeasonID AS tsid

				FROM fixtureLineup AS fixl

				INNER JOIN lineupStatus AS ls
				ON fixl.lineupStatusID=ls.ID

				INNER JOIN teamPlayers AS tp
				ON fixl.teamPlayerID=tp.ID

				INNER JOIN person AS p
				ON tp.personID=p.ID

				WHERE fixl.fixtureID=$fixtureID 
				AND tp.teamSeasonID=$atid
				AND fixl.lineupStatusID=2";

				$result = mysql_query($sql);

				?>

				<table class="bordered" width="100%">
				<thead>
				<tr>
				<th>ID</th>
				<th>Substitute</th>
				</tr>
				</thead>

				<?php
				while($row = mysql_fetch_assoc($result)) {
					?>
					<tr>
				  <td><?php echo $row['ID'] ?></td>
				  <td><?php echo $row['num'] .". " . $row['pfname'] ." ". $row['plname'] ?></td>
				  <td>update  delete</td>
				  </tr>
				  <?php 


				}
				echo "</table>"; ?>
	</td>
	</tr>
	</table>
	<?php
} else {

	?>

<table width="100%">
	<thead>
	<tr>
	<th><?php echo fixture_home_heading($fixtureID) ?></th>
	<th><?php echo fixture_away_heading($fixtureID) ?></th>
	</tr>
	</thead>
<tr>
	<td valign="top">
				<?php 
				$sql=
				"SELECT
				fixl.ID AS ID,
				fixl.fixtureID AS fixid,
				fixl.teamPlayerID AS pid,

				p.firstName AS pfname,
				p.lastName AS plname,

				fixl.lineupStatusID AS lsid,
				ls.name AS lsname,

				tp.number AS num,
				tp.teamSeasonID AS tsid

				FROM fixtureLineup AS fixl

				INNER JOIN lineupStatus AS ls
				ON fixl.lineupStatusID=ls.ID

				INNER JOIN teamPlayers AS tp
				ON fixl.teamPlayerID=tp.ID

				INNER JOIN person AS p
				ON tp.personID=p.ID

				WHERE fixl.fixtureID=$fixtureID 
				AND tp.teamSeasonID=$htid
				AND fixl.lineupStatusID=1";

				$result = mysql_query($sql);

				?>

				<table class="bordered" width="100%">
				<thead>
				<tr>
				<th>ID</th>
				<th>Starting</th>
				</tr>
				</thead>

				<?php
				while($row = mysql_fetch_assoc($result)) {
					?>
					<tr>
				  <td><?php echo $row['ID'] ?></td>
				  <td><?php echo $row['num'] .". " . $row['pfname'] ." ". $row['plname'] ?></td>
				  <td>update  delete</td>
				  </tr>
				  <?php 


				}
				echo "</table>"; ?>

				<?php 
				$sql=
				"SELECT
				fixl.ID AS ID,
				fixl.fixtureID AS fixid,
				fixl.teamPlayerID AS pid,

				p.firstName AS pfname,
				p.lastName AS plname,

				fixl.lineupStatusID AS lsid,
				ls.name AS lsname,

				tp.number AS num,
				tp.teamSeasonID AS tsid

				FROM fixtureLineup AS fixl

				INNER JOIN lineupStatus AS ls
				ON fixl.lineupStatusID=ls.ID

				INNER JOIN teamPlayers AS tp
				ON fixl.teamPlayerID=tp.ID

				INNER JOIN person AS p
				ON tp.personID=p.ID

				WHERE fixl.fixtureID=$fixtureID 
				AND tp.teamSeasonID=$htid
				AND fixl.lineupStatusID=2";

				$result = mysql_query($sql);

				?>

				<table class="bordered" width="100%">
				<thead>
				<tr>
				<th>ID</th>
				<th>Substitutes</th>
				</tr>
				</thead>

				<?php
				while($row = mysql_fetch_assoc($result)) {
					?>
					<tr>
				  <td><?php echo $row['ID'] ?></td>
				  <td><?php echo $row['num'] .". " . $row['pfname'] ." ". $row['plname'] ?></td>
				  <td>update  delete</td>
				  </tr>
				  <?php 


				}
				echo "</table>"; ?>
	</td>

	<td valign="top">
				<?php 
				$sql=
				"SELECT
				fixl.ID AS ID,
				fixl.fixtureID AS fixid,
				fixl.teamPlayerID AS pid,

				p.firstName AS pfname,
				p.lastName AS plname,

				fixl.lineupStatusID AS lsid,
				ls.name AS lsname,

				tp.number AS num,
				tp.teamSeasonID AS tsid

				FROM fixtureLineup AS fixl

				INNER JOIN lineupStatus AS ls
				ON fixl.lineupStatusID=ls.ID

				INNER JOIN teamPlayers AS tp
				ON fixl.teamPlayerID=tp.ID

				INNER JOIN person AS p
				ON tp.personID=p.ID

				WHERE fixl.fixtureID=$fixtureID 
				AND tp.teamSeasonID=$atid
				AND fixl.lineupStatusID=1";

				$result = mysql_query($sql);

				?>

				<table class="bordered" width="100%">
				<thead>
				<tr>
				<th>ID</th>
				<th>Starting</th>
				</tr>
				</thead>

				<?php
				while($row = mysql_fetch_assoc($result)) {
					?>
					<tr>
				  <td><?php echo $row['ID'] ?></td>
				  <td><?php echo $row['num'] .". " . $row['pfname'] ." ". $row['plname'] ?></td>
				  <td>update  delete</td>
				  </tr>
				  <?php 


				}
				echo "</table>"; ?>

				<?php 
				$sql=
				"SELECT
				fixl.ID AS ID,
				fixl.fixtureID AS fixid,
				fixl.teamPlayerID AS pid,

				p.firstName AS pfname,
				p.lastName AS plname,

				fixl.lineupStatusID AS lsid,
				ls.name AS lsname,

				tp.number AS num,
				tp.teamSeasonID AS tsid

				FROM fixtureLineup AS fixl

				INNER JOIN lineupStatus AS ls
				ON fixl.lineupStatusID=ls.ID

				INNER JOIN teamPlayers AS tp
				ON fixl.teamPlayerID=tp.ID

				INNER JOIN person AS p
				ON tp.personID=p.ID

				WHERE fixl.fixtureID=$fixtureID
				AND tp.teamSeasonID=$atid
				AND fixl.lineupStatusID=2";

				$result = mysql_query($sql);

				?>

				<table class="bordered" width="100%">
				<thead>
				<tr>
				<th>ID</th>
				<th>Substitutes</th>
				</tr>
				</thead>

				<?php
				while($row = mysql_fetch_assoc($result)) {
					?>
					<tr>
				  <td><?php echo $row['ID'] ?></td>
				  <td><?php echo $row['num'] .". " . $row['pfname'] ." ". $row['plname'] ?></td>
				  <td>update  delete</td>
				  </tr>
				  <?php 


				}
				echo "</table>"; ?>
	</td>
	</tr>
	</table>
<?php





}

// end display page=lineup&view=add

?>