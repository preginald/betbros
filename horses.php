<br>
<h1>Race Horses <a href="admin.php?page=horses&view=add"><img alt="add" style="width: 24px; height: 24px;" title="add" src="images/add-record.png"></a></h1>
<?php 
print_r_pre($_POST);
// form processors start 
// add horse to database process
// check if form button = submit 

if(isset($_POST['add_horse'])){

	if (!empty($_POST['owner_lname'])) {
		$ofname=$_POST['owner_fname'];
		$omname=$_POST['owner_mname'];
		$olname=$_POST['owner_lname'];
		$ocID=$_POST['ocID'];
		$osex=$_POST['osex'];
		$createTimestamp = date("Y-m-d H:i:s");

		$sql=
		"INSERT INTO `person`(
			`genderID`, 
		    `firstName`, 
		    `middleName`,
		    `lastName`, 
		    `countryID`, 
		    `createUserID`, 
		    `createTimestamp`
		    ) VALUES(
		    '$osex',
		    '$ofname',
		    '$omname',
		    '$olname',
		    '$ocID',
		    '$session_user_id',
		    '$createTimestamp'
		    )";
		$result=mysql_query($sql);
		$pID=mysql_insert_id();
		if($result){
			//successfully added person

			$sql=
			"INSERT INTO `horseOwner`(
				`personID`,
				`createUserID`, 
				`createTimestamp`
				) VALUES (
				'$pID',
			    '$session_user_id',
			    '$createTimestamp'
				)";
			$result=mysql_query($sql);
			$horseOwnerID=mysql_insert_id();
			if($result){
				//successfully added horse owner
			} else {
				echo "ERROR: HORSE OWNER ADD";
			}
		} else {
			echo "ERROR: PERSON ADD";
		}
	} else {
		$horseOwnerID = $_POST['horseOwnerID'];

		$result = mysql_query("SELECT `personID` FROM `horseOwner` WHERE `ID` = $horseOwnerID");
		$row = mysql_fetch_row($result);
		$pID=$row[0];
	}

	if (isset($_POST['copy']) && $_POST['copy']==1) {
		$sql=
		"INSERT INTO `horseTrainer`(
			`personID`,
			`createUserID`, 
			`createTimestamp`
			) VALUES (
			'$pID',
		    '$session_user_id',
		    '$createTimestamp'
			)";
		$result=mysql_query($sql);
		$horseTrainerID=mysql_insert_id();
		if($result){
			//successfully added horse trainer
		} else {
			echo "ERROR: HORSE TRAINER ADD";
		}
	} elseif (!empty($_POST['tlname'])) {
		$tfname=$_POST['tfname'];
		$tmname=$_POST['tmname'];
		$tlname=$_POST['tlname'];
		$tcID=$_POST['tcID'];
		$tsex=$_POST['tsex'];
		$createTimestamp = date("Y-m-d H:i:s");

		$sql=
		"INSERT INTO `person`(
			`genderID`, 
		    `firstName`, 
		    `middleName`, 
		    `lastName`, 
		    `countryID`, 
		    `createUserID`, 
		    `createTimestamp`
		    ) VALUES(
		    '$tsex',
		    '$tfname',
		    '$tmname',
		    '$tlname',
		    '$tcID',
		    '$session_user_id',
		    '$createTimestamp'
		    )";
		$result=mysql_query($sql);
		$pID=mysql_insert_id();
		if($result){
			//successfully added person

			$sql=
			"INSERT INTO `horseTrainer`(
				`personID`,
				`createUserID`, 
				`createTimestamp`
				) VALUES (
				'$pID',
			    '$session_user_id',
			    '$createTimestamp'
				)";
			$result=mysql_query($sql);
			$horseTrainerID=mysql_insert_id();
			if($result){
				//successfully added horse owner
			} else {
				echo "ERROR: HORSE OWNER ADD";
			}
		} else {
			echo "ERROR: PERSON ADD";
		}
	} else {
		$horseTrainerID = $_POST['horseTrainerID'];
	}

	$name = $_POST['name'];
	$birth = $_POST['birth'];
	$genderID = $_POST['genderID'];
	$countryID = $_POST['countryID'];
	$createTimestamp = date("Y-m-d H:i:s");

	$sql_add_horse = 
	"INSERT INTO horses (name,birth, genderID, OwnerID, TrainerID, CountryID, createUserID, createTimestamp) 
	VALUES ('$name','$birth','$genderID','$horseOwnerID','$horseTrainerID','$countryID','$session_user_id','$createTimestamp')";

	$result=mysql_query($sql_add_horse);

	// if successfully updated. 

	if($result){
		echo "successfully added $name";
	} else {
		echo "ERROR";
	}
}

if(isset($_POST['update_horse'])){
	$name = $_POST['name'];
	$birth = $_POST['birth'];
	$genderID = $_POST['genderID'];
	$OwnerID = $_POST['horseOwnerID'];
	$TrainerID = $_POST['horseTrainerID'];
	$countryID = $_POST['countryID'];
	$modifyTimestamp = date("Y-m-d H:i:s");

	$sql_update_horse = 
	"UPDATE `horses` SET `name`='$name',`birth`='$birth',`genderID`='$genderID',
	`OwnerID`='$OwnerID',`TrainerID`='$TrainerID',`CountryID`='$countryID',
	`modifyUserID`='$session_user_id',`modifyTimestamp`='$modifyTimestamp' 
	WHERE `ID` = '$horsesID'
	";

	$result=mysql_query($sql_update_horse);

	// if successfully updated. 

	if($result){
		echo "successfully updated $name";
	} else {
	echo "ERROR";
	}
}

// start dispay entire list if view=list
if(isset($_GET['view']) && $_GET['view']=="list"){
	// perform sql query for search box
	$sql_search = mysql_query(
		"SELECT 
		h.ID AS ID, 
		h.name AS hname, 
		h.birth AS birth, 
		sex.name AS sex,

		o.firstName AS ofname,
		o.middleName AS omname,
		o.lastName AS olname,

		t.firstName AS tfname,
		t.middleName AS tmname,
		t.lastName AS tlname,

		c.alpha_2 As c
				
		FROM horses AS h

		JOIN horseGender AS sex
		ON h.genderID=sex.ID

		LEFT JOIN horseOwner AS ho
		ON h.OwnerID=ho.ID

		LEFT JOIN horseTrainer AS ht
		ON h.trainerID=ht.ID

		JOIN person AS o
		ON ho.personID = o.ID

		JOIN person AS t
		ON ht.personID = t.ID

		JOIN countries AS c
		ON h.countryID=c.id 
		");
	?>
	<!-- results displayed  as table - table head -->
	<table class="bordered">
	<thead>
	<tr>
	<th>ID</th>
	<th>Horse</th>
	<th>Foaled</th>
	<th>Sex</th>
	<th>Owner</th>
	<th>Trainer</th>
	<th>Update</th>
	</tr>
	</thead>

	<?php
	// start display horse list table

	while($row = mysql_fetch_assoc($sql_search)) {

		echo "<tr>";
		echo "<td>" . $row['ID'] . "</td>"; // horseID
		echo "<td>" . $row['hname'] . " (" . $row['c'] .")</td>"; // horse name
		echo "<td>" . $row['birth'] . " (" . (date("Y") - $row['birth']) . ")" . "</td>";
		echo "<td>" . $row['sex'] . "</td>"; // sex
		echo "<td>" . $row['ofname'] ." " . $row['omname'] ." " . $row['olname'] . "</td>";
		echo "<td>" . $row['tfname'] ." " . $row['tmname'] ." " . $row['tlname'] . "</td>";
		echo "<td align='center'><a href=" . "admin.php?horses=edit&horsesid=". $row['ID'] . ">update</a></td>";
		echo "</tr>";
	}

	echo "</table>";

	// end display horse list table
}

// View Horse info if horse ID is set in URL
if(isset($_GET['id'])){ 
	$horsesID = $_GET['id'];
	echo 'displaying horse info page';

	$sql = "SELECT horses.ID, horses.name, horses.birth, horseGender.name, horseOwner.lastName, horseTrainer.lastName, countries.name
	FROM horses
	JOIN horseGender
	ON horses.genderID=horseGender.ID
	JOIN horseOwner
	ON horses.OwnerID=horseOwner.ID
	JOIN horseTrainer
	ON horses.trainerID=horseTrainer.ID
	JOIN countries
	ON horses.countryID=countries.id
	WHERE horses.ID = $horsesID
	LIMIT 15
	";

	$result = mysql_query($sql);

	?>
	<table id="sortable" class="bordered">
	<thead>
	<tr>
	<th>ID</th>
	<th>Horse</th>
	<th>Birth</th>
	<th>Sex</th>
	<th>Owner</th>
	<th>Trainer</th>
	<th>Country</th>
	<th>Update</th>
	</tr>
	</thead>

	<?php

	// start display horse list table

	while($row = mysql_fetch_array($result)) {

	  echo "<tr>";
	  echo "<td>" . $row['ID'] . "</td>";
	  echo "<td>" . $row['1'] . "</td>";
	  echo "<td>" . $row['2'] . " (" . (date("Y") - $row['2']) . ")" . "</td>";
	  echo "<td>" . $row['3'] . "</td>";
	  echo "<td>" . $row['4'] . "</td>";
	  echo "<td>" . $row['5'] . "</td>";
	  echo "<td>" . $row['6'] . "</td>";
	  echo "<td align='center'><a href=" . "admin.php?horses=edit&horsesid=". $row['ID'] . ">update</a></td>";
	  echo "</tr>";
	  }
	echo "</table>";

	// end display horse list table

}

?>

<br>
<br>
<?php 
// dispay input form if horses=add
if(isset($_GET['view']) && $_GET['view']=="add"){ ?>
	<h2>Add new horse</h2>
	<form name="form1" method="post" action="" >
	Horse Name:<input type="text" name="name" placeholder="Horse name" required><br/>
	Foaled:<input type="date" name="birth" placeholder="Birth year" required><br/>
	Gender:<select name="genderID"><?php horse_sex_dropDown() ?></select><br/>
	Country:<select name="countryID"><?php country_dropDown() ?></select>
	<h3>Owner</h3>
	<select name="horseOwnerID"><?php horse_owner_dropDown() ?></select><br/>
	Or Add New Owner: 
	<select name="osex"><?php person_gender_dropdown() ?></select>
	<input type="text" name="owner_fname" placeholder="First Name">
	<input type="text" name="owner_mname" placeholder="Middle Name">
	<input type="text" name="owner_lname" placeholder="Last Name">
	<select name="ocID"><?php country_dropDown() ?></select><br/>
	
	<h3>Trainer</h3>
	<select name="horseTrainerID"><?php horse_trainer_dropDown() ?></select><br/>
	Or Copy Owner's details <input type="checkbox" name="copy" value="1"><br/>
	Or Add New Trainer:  
	<select name="tsex"><?php person_gender_dropdown() ?></select>
	<input type="text" name="tfname" placeholder="First Name">
	<input type="text" name="tmname" placeholder="Middle Name">
	<input type="text" name="tlname" placeholder="Last Name">
	<select name="tcID"><?php country_dropDown() ?></select><br/>
	
	<input type="submit" name="add_horse" value="Add">
	</form>
	<?php 
} 


// start edit Horse process
if(isset($_GET['view']) && $_GET['view']=="edit"){ 

	$horsesID = $_GET['horsesid'];

	$sql_sh = "SELECT * FROM horses WHERE ID = $horsesID";
	$result_sh = mysql_query($sql_sh);
	$rows_sh =  mysql_fetch_assoc($result_sh);
	// print_r($rows_sh);

	$name = $rows_sh['name'];
	$birth = $rows_sh['birth'];
	$genderID = $rows_sh['genderID'];
	$OwnerID = $rows_sh['OwnerID'];
	$TrainerID = $rows_sh['TrainerID'];
	$countryID = $rows_sh['CountryID'];
	$modifyTimestamp = date("Y-m-d H:i:s");

	?>

	<h2>Edit horse</h2>
	<form name="form1" method="post" action="" >
	<input type="text" name="name" placeholder="Horse name" value="<?php echo $name ?>">
	<input type="number" name="birth" min="2006" max="2013" placeholder="Birth year" value="<?php echo $birth ?>">
	<select name="genderID"><?php horse_sex_dropDown() ?></select>
	<select name="horseOwnerID"><?php horse_owner_dropDown() ?></select>
	<select name="horseTrainerID"><?php horse_trainer_dropDown() ?></select>
	<select name="countryID"><?php country_dropDown() ?></select>
	<input type="submit" name="update_horse" value="Update">
	</form>

	<?php 
	// add horse to database process
	// check if form button = Update 



	?>



<?php 
} 
?>