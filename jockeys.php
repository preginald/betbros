<br>
<h1>Jockeys <a href="admin.php?page=jockeys&view=add"><img alt="add" style="width: 24px; height: 24px;" title="add" src="images/add-record.png"></a></h1>
<?php 
//form processing
//print_r_pre($_POST);

if(isset($_POST['Add'])){
	$jsex = $_POST['jsex'];
	$jfname = $_POST['jfname'];
	$jmname = $_POST['jmname'];
	$jlname = $_POST['jlname'];
	$dob = $_POST['dob'];
	$countryID = $_POST['countryID'];
	$createTimestamp = date("Y-m-d H:i:s");

	$sql = 
	"INSERT INTO person (genderID,firstName,middleName,lastName,dob,countryID,createUserID, createTimestamp)
	VALUES ('$jsex','$jfname','$jmname','$jlname','$dob','$countryID','$session_user_id','$createTimestamp')";
	$result=mysql_query($sql);
	$pID=mysql_insert_id();

	if ($result) {
		//successfully added person

		$sql_add_jockey = 
		"INSERT INTO jockeys (personID, createUserID, createTimestamp) 
		VALUES ('$pID','$session_user_id','$createTimestamp')";
			
		// echo $sql_add_jockey; //debug
			
		$result=mysql_query($sql_add_jockey);

		// if successfully updated. 

		if($result){
			echo "Successfully added Jockey: $jfname $jmname $jlname.";
		} else {
			echo "ERROR";
		}
	} else {
		echo "ERROR: PERSON ADD";
	}
}

if(isset($_POST['Update'])){
		$firstName = $_POST['firstName'];		
		$lastName = $_POST['lastName'];
		$countryID = $_POST['countryID'];
		$modifyTimestamp = date("Y-m-d H:i:s");


		$sql_update_jockey = 
		"UPDATE `horseTrainer` SET `firstName`='$firstName',`lastName`='$lastName',`CountryID`='$countryID',
		`modifyUserID`='$session_user_id',`modifyTimestamp`='$modifyTimestamp' 
		WHERE `ID` = '$trainerID'
		";
		
		// echo $sql_update_jockey; // debug

		$result=mysql_query($sql_update_jockey);

		// if successfully updated. 

		if($result){

			echo "successfully updated $firstName $lastName";

		} else {

		echo "ERROR";

		}
}

if(isset($_GET['view']) && $_GET['view']=="list"){
	$sql = 
	"SELECT 
	j.ID AS ID, 
	p.firstName AS jfname, 
	p.middleName AS jmname,
	p.lastName AS jlname,
	c.alpha_2 AS c
		
	FROM jockeys AS j

	INNER JOIN person AS p
	ON j.personID = p.ID
		
	JOIN countries AS c
	ON p.countryID = c.id";

	$result = mysql_query($sql);

	?>
	<table class="bordered">
	<thead>
	<tr>
	<th>ID</th>
	<th>Name</th>
	<th>Update</th>
	</tr>
	</thead>

	<?php

	while($row = mysql_fetch_array($result)) {
		echo "<tr>";
		echo "<td>" . $row['ID'] . "</td>";
		echo "<td>" . $row['jfname'] ." ". $row['jmname'] ." ". $row['jlname'] ." (". $row['c'] . ")</td>";
		echo "<td align='center'><a href=" . "admin.php?jockeys=edit&jockeyid=". $row['ID'] . ">update</a></td>";
		echo "</tr>";
	}
	echo "</table>";
}

?>

<br>
<br>
<?php 
// dispay input form if jockeys=add
if(isset($_GET['view']) && $_GET['view']=="add"){ ?>

	<h2>Add New Jockey</h2>
	<form name="form1" method="post" action="" >
	<select name="jsex"><?php person_gender_dropdown() ?></select>
	<input type="text" name="jfname" placeholder="First name">
	<input type="text" name="jmname" placeholder="Middle name">
	<input type="text" name="jlname" placeholder="Last name">
	<input type="date" name="dob" placeholder="Birth year" >
	<select name="countryID" required ><?php country_dropDown() ?></select>
	<input type="submit" name="Add" value="Add">
	</form>

	<?php 
} 

	// add jockey to database process
	// check if form button = submit 


// Jockey edit process
if(isset($_GET['view']) && $_GET['view']=="edit"){ 

	$jockeyID = $_GET['id'];

	$sql_sj = "SELECT * FROM jockeys WHERE ID = $jockeyID";
	$result_sj = mysql_query($sql_sj);
	$rows_sj =  mysql_fetch_assoc($result_sj);
	print_r($rows_sj);

	$firstName = $rows_sj['firstName'];
	$lastName = $rows_sj['lastName'];
	$countryID = $rows_sj['countryID'];
	$modifyTimestamp = date("Y-m-d H:i:s");

?>

<h2>Edit Jockey</h2>
<form name="form1" method="post" action="" class='ajax'>
<input type="text" name="firstName" placeholder="First name" value="<?php echo $firstName ?>">
<input type="text" name="lastName" placeholder="Last name" value="<?php echo $lastName ?>">
<select name="countryID"><?php country_dropDown() ?></select>
<input type="submit" name="Update" value="Update">
</form>

<?php } ?>