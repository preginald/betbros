<br>
<h1>Horse Trainers <a href="admin.php?horse-trainers=add"><img alt="add" style="width: 24px; height: 24px;" title="add" src="images/add-record.png"></a></h1>
<?php 
// start search box code
?>
<form name ="search" action="<?=$PHP_SELF?>" method="POST">
<input type="text" name="term" size="50" placeholder="Enter Search Term"> <b>Results:</b> <select name="results">
    <option>10</option>
    <option>20</option>
    <option>50</option>
</select><br>
<input type="hidden" name="searching" value="yes" />
<input type="submit" value="Search">
</form>

<?php
if ($_POST[searching] =="yes") {

	// perform sql query for search box
	$sql_search = mysql_query(
		"SELECT horseTrainer.ID, horseTrainer.firstName, horseTrainer.lastName, countries.name
		FROM horseTrainer
		JOIN countries
		ON horseTrainer.countryID=countries.id
		WHERE horseTrainer.lastName 
		LIKE '%$_POST[term]%' 
		LIMIT 0,$_POST[results]");

	?>

	<table id="sortable" class="bordered">
	<thead>
	<tr>
	<th>ID</th>
	<th>First Name</th>
	<th>Last Name</th>
	<th>Country</th>
	<th>Update</th>
	</tr>
	</thead>

	<?php

	while($row = mysql_fetch_array($sql_search)) {

	  echo "<tr>";
	  echo "<td>" . $row['ID'] . "</td>";
	  echo "<td>" . $row['1'] . "</td>";
	  echo "<td>" . $row['2'] . "</td>";
	  echo "<td>" . $row['3'] . "</td>";
	  echo "<td align='center'><a href=" . "admin.php?horse-trainers=edit&trainerid=". $row['ID'] . ">update</a></td>";
	  echo "</tr>";
	  }
	echo "</table>";

}

// start dispay entire list if horse-trainers=list
if(isset($_GET['horse-trainers']) && $_GET['horse-trainers']=="list"){

$sql = "SELECT horseTrainer.ID, horseTrainer.firstName, horseTrainer.lastName, countries.name
FROM horseTrainer
JOIN countries
ON horseTrainer.countryID=countries.id";

$result = mysql_query($sql);

?>
<table id="sortable" class="bordered">
<thead>
<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Country</th>
<th>Update</th>
</tr>
</thead>

<?php

while($row = mysql_fetch_array($result)) {

  echo "<tr>";
  echo "<td>" . $row['ID'] . "</td>";
  echo "<td>" . $row['1'] . "</td>";
  echo "<td>" . $row['2'] . "</td>";
  echo "<td>" . $row['3'] . "</td>";
  echo "<td align='center'><a href=" . "admin.php?horse-trainers=edit&trainerid=". $row['ID'] . ">update</a></td>";
  echo "</tr>";
  }
echo "</table>";
}

// end dispay entire list if horse-trainers=list
?>

<br>
<br>
<?php 
// dispay input form if horse-trainers=add
if(isset($_GET['horse-trainers']) && $_GET['horse-trainers']=="add"){ ?>

<h2>Add new trainer</h2>
<form name="form1" method="post" action="" class='ajax'>
<input type="text" name="firstName" placeholder="First name">
<input type="text" name="lastName" placeholder="Last name">
<select name="countryID"><?php country_dropDown() ?></select>
<input type="submit" name="Add" value="Add">
</form>

<?php } 

// add trainer to database process
// check if form button = submit 

if(isset($_POST['Add'])){
	echo 'starting add process';
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$countryID = $_POST['countryID'];
	$createTimestamp = date("Y-m-d H:i:s");

	$sql_add_trainer = "INSERT INTO horseTrainer (firstName,lastName,countryID, createUserID, createTimestamp) 

			VALUES ('$firstName','$lastName','$countryID','$session_user_id','$createTimestamp')";
	
	// echo $sql_add_trainer; //debug
	
	$result=mysql_query($sql_add_trainer);

	// if successfully updated. 

	if($result){

		echo "successfully added trainer";

	} else {

		echo "ERROR";

	}
}

// Trainer edit process
if(isset($_GET['horse-trainers']) && $_GET['horse-trainers']=="edit"){ 

	$trainerID = $_GET['trainerid'];

	$sql_st = "SELECT * FROM horseTrainer WHERE ID = $trainerID";
	$result_st = mysql_query($sql_st);
	$rows_st =  mysql_fetch_assoc($result_st);
	print_r($rows_st);

	$firstName = $rows_st['firstName'];
	$lastName = $rows_st['lastName'];
	$countryID = $rows_st['countryID'];
	$modifyTimestamp = date("Y-m-d H:i:s");

?>

<h2>Edit trainer</h2>
<form name="form1" method="post" action="" class='ajax'>
<input type="text" name="firstName" placeholder="First name" value="<?php echo $firstName ?>">
<input type="text" name="lastName" placeholder="Last name" value="<?php echo $lastName ?>">
<select name="countryID"><?php country_dropDown() ?></select>
<input type="submit" name="Update" value="Update">
</form>

<?php 
	// add horse-trainer to database process
	// check if form button = submit 

	if(isset($_POST['Update'])){
		$firstName = $_POST['firstName'];		
		$lastName = $_POST['lastName'];
		$countryID = $_POST['countryID'];
		$modifyTimestamp = date("Y-m-d H:i:s");


		$sql_update_trainer = 
		"UPDATE `horseTrainer` SET `firstName`='$firstName',`lastName`='$lastName',`CountryID`='$countryID',
		`modifyUserID`='$session_user_id',`modifyTimestamp`='$modifyTimestamp' 
		WHERE `ID` = '$trainerID'
		";
		
		// echo $sql_update_trainer; // debug

		$result=mysql_query($sql_update_trainer);

		// if successfully updated. 

		if($result){

			echo "successfully updated $firstName $lastName";

		} else {

		echo "ERROR";

		}

	}


	?>



<?php } ?>