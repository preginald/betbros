<?php
protect_page();
admin_protect();

echo "<br><br>";

if(isset($_GET['view']) && $_GET['view']=="list"){

$sql = "SELECT
t.ID AS tid,
t.name AS tname,
s.ID AS sid,
s.name AS sname,
c.name AS cname,
c.alpha_2 AS calpha
FROM teams AS t
INNER JOIN sports AS s
ON s.ID=t.sportID
INNER JOIN countries AS c
ON c.id=t.countryID";



$result = mysql_query($sql);

?>
<table border='0' class="bordered">

<tr>

<th>ID</th>

<th>Team</th>

<th>Country</th>

<th>Sport</th>

<th>Update</th>

</tr>
<?php while($row = mysql_fetch_assoc($result)) {


  echo "<tr>";

  echo "<td>" . $row['tid'] . "</td>";

  echo "<td>" . $row['tname'] . "</td>";

  echo "<td>" . $row['cname'] . "</td>";

  echo "<td>" . $row['sname'] . "</td>";

  echo "<td align='center'><a href=" . "edit-teams.php?id=". $row['ID'] . ">update</a></td>";

  echo "</tr>";

  }

echo "</table>";

}

// start display team view=add
// start input form
if(isset($_GET['view']) && $_GET['view']=="add"){ ?>
<h1>Add new team</h1>

<form name="add-team" method="post" action="<?=$PHP_SELF?>" >

<div>
<span class="small">Team Name</span>
<input type="text" name="teamName" placeholder="Team name" value="<?php echo $teamName ?>">
</div>

<div>
<span class="small">Country</span>
<select name="countryID"><?php country_dropDown() ?></select>
</div>

<div>
<span class="small">Sport</span>
<select name="sportID"><?php sports_dropdown() ?></select>
</div>

<input type="submit" name="Submit" value="Add">

</form>

<?php } 

// end input form
// end display teamSeason view=add

// start add eventSeason to database process
// check if form button = submit 

if(isset($_POST['Submit'])){
	echo $teamName = $_POST['teamName'];
	echo $countryID = $_POST['countryID'];
	echo $sportID = $_POST['sportID'];
	echo $createTimestamp = date("Y-m-d H:i:s");

	// check if event already exists
	$sql_check_team= "SELECT * FROM `teams` WHERE `name` = '$teamName'";

	echo $sql_check_team;
	$result=mysql_query($sql_check_team);
	if (mysql_fetch_row($result)) {

		echo "This team already exists in this event";

	} else {

		echo $sql_add_team = 
		"INSERT INTO `teams`
				(`name`, `countryID`, `sportID`, `createUserID`, `createTimestamp`) 
		VALUES  ('$teamName','$countryID','$sportID','$session_user_id','$createTimestamp')";

		$result=mysql_query($sql_add_team);

		// end add event to database process
	// if successfully updated. 

		if($result){

			echo "successfully added team";

			} else {

				echo "ERROR";

				}
			}

		}
// end add event process

// start hide/show last 5 added teams


		if(isset($_GET['history'])){
			echo "<hr>";
			echo "<h2>Last 5 teams added</h2>";

			$sql=
			"SELECT
			t.ID AS tid,
			t.name AS tname,
			s.ID AS sid,
			s.name AS sname,
			c.name AS cname,
			c.alpha_2 AS calpha,
			t.createTimestamp AS crtime
			FROM teams AS t
			INNER JOIN sports AS s
			ON s.ID=t.sportID
			INNER JOIN countries AS c
			ON c.id=t.countryID
			ORDER BY crtime DESC 
			LIMIT 5
			";

			$result = mysql_query($sql);

			?>

			<table class="bordered">
			<thead>
			<tr>
			<th>ID</th>
			<th>Team</th>
			<th>Country</th>
			<th>Sport</th>
			<th>Date Created</th>
			</tr>
			</thead>

			<?php
			while($row=mysql_fetch_assoc($result)){
				// print_r($row);
				?>

				  <tr>
				  <td><?php echo $row['tid'] ?></td>
				  <td><?php echo $row['tname'] ?></td>
				  <td><?php echo $row['cname'] ?></td>
				  <td><?php echo $row['sname'] ?></td>
				  <td><?php echo $row['crtime'] ?></td>
				  </tr>
				<?php 
				  }

				  echo "</table>";
	}
	// end display teamSeason view=list

// end hide/show last 5 added teams


?>