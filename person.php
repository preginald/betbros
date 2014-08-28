<?php print_r_pre($_POST); ?>
<script type="text/javascript">
$(document).ready(function(){
$("#name").autocomplete({source:'autocomplete_person.php', minLength:1});
});
</script>

<br>
<h1>Person <a href="admin.php?page=person&view=add"><img alt="add" style="width: 24px; height: 24px;" title="add" src="images/add-record.png"></a></h1>
<?php 
$personID = mysql_escape_string($_GET['id']);

if(isset($_POST['add_person'])){
	// start add person to database process
	// check if form button = submit 
	$genderID = $_POST['genderID'];
	$firstName = $_POST['firstName'];
	$middleName = $_POST['middleName'];
	//$middleName = $_POST['ht'];
	$lastName = $_POST['lastName'];
	$dob = $_POST['dob'];
	$height = $_POST['height'];
	$countryID = $_POST['countryID'];
	$ptypes = $_POST['ptype'];
	$createTimestamp = date("Y-m-d H:i:s");

	// check if person already exists
	$sql_check_person= "SELECT * FROM `person` WHERE `firstName` = '$firstName' AND `lastName` = '$lastName'";
	$result=mysql_query($sql_add_person);
	if($result){

		echo "$firstName and $lastName already exists";

	} else {

		$sql_add_person = 
		"INSERT INTO `person`(`genderID`, `firstName`, `middleName`, `lastName`, `dob`, `countryID`, `height`, `createUserID`, `createTimestamp`) 
		VALUES ('$genderID','$firstName','$middleName','$lastName','$dob','$countryID','$height','$session_user_id','$createTimestamp')";

		$result=mysql_query($sql_add_person);
		$personID=mysql_insert_id();

	// end add person to database process
	// if successfully updated. 

		if($result){

			echo "successfully added person";

			if (isset($_POST['j'])) {
			# code...
			}elseif ($_POST['ht']=='ht') {
				$sql_add_ht=
				"INSERT INTO `horseTrainer`(`personID`, `createUserID`, `createTimestamp`) 
				VALUES ('$personID','$session_user_id','$createTimestamp')";
				$result=mysql_query($sql_add_ht);
				if ($result) {
					//insert ok
				}else{ 
					echo "ERROR";
				}		
			}elseif (isset($_POST['ho'])) {
				# code...
			}

		} else {
			echo "ERROR";
		}
	}
}

if(isset($_POST['update_person'])){
	// add person to database process
	// check if form button = Update 

	$genderID = $_POST['genderID'];
	$firstName = $_POST['firstName'];
	$middleName = $_POST['middleName'];
	$lastName = $_POST['lastName'];
	$dob = $_POST['dob'];
	$height = $_POST['height'];
	$countryID = $_POST['countryID'];
	$modifyTimestamp = date("Y-m-d H:i:s");

	$sql_update_person = 
	"UPDATE `person` SET `genderID`='$genderID',`firstName`='$firstName',`middleName`='$middleName',
	`lastName`='$lastName',`dob`='$dob',`countryID`='$countryID',`height`='$height',
	`modifyUserID`='$session_user_id',`modifyTimestamp`='$modifyTimestamp' 
	WHERE `ID` = '$personID'
	";

	// echo $sql_update_person; // debug

	$result=mysql_query($sql_update_person);

	// if successfully updated. 
	if($result){
		echo "successfully updated $firstName $lastName";
	} else {
	echo "ERROR";
	}
}


// start search box code
?>
<form name ="search" action="<?=$PHP_SELF?>" method="POST">
<input type="text" id="name" name="term" size="50" placeholder="Enter Search Term"> 
<b>Results:</b> 
<select name="results">
    <option>10</option>
    <option>20</option>
    <option>50</option>
</select><br>
<input type="hidden" name="searching" value="yes" />
<input type="submit" value="Search">
</form>

<?php 
// perform sql query for search box
$sql_search = mysql_query(
	"SELECT person.ID, personGender.name, personGender.code, person.firstName, person.middleName, person.lastName, person.dob, person.height, countries.name
	FROM person
	JOIN personGender
	ON person.genderID=personGender.ID
	JOIN countries
	ON person.countryID=countries.id 
	WHERE person.lastName LIKE '%$_POST[term]%' 
	OR person.firstName LIKE '%$_POST[term]%' 
	OR person.middleName LIKE '%$_POST[term]%' 
	LIMIT 0,$_POST[results]");

// start display person list table

if ($_POST['searching'] =="yes") {

	// results displayed  as table - table head
	echo '<table id="sortable" class="bordered">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>ID</th>';
	echo '<th>Gender</th>';
	echo '<th>First</th>';
	echo '<th>Middle</th>';
	echo '<th>Last</th>';
	echo '<th>DOB</th>';
	echo '<th>Height</th>';
	echo '<th>Country</th>';
	echo '<th>Update</th>';
	echo '</tr>';
	echo '</thead>';

	while($row = mysql_fetch_array($sql_search)) {

		echo "<tr>";
		echo "<td>" . $row['ID'] . "</td>"; // person ID
		echo "<td>" . $row['1'] . "</td>"; // gender name
		echo "<td>" . $row['3'] . "</td>"; // first name
		echo "<td>" . $row['4'] . "</td>"; // middle name
		echo "<td>" . $row['5'] . "</td>"; // last name
		echo "<td>" . $row['6'] . "</td>"; // dob
		echo "<td>" . $row['7'] . "</td>"; // height
		echo "<td>" . $row['8'] . "</td>"; // country
		echo "<td align='center'><a href=" . "admin.php?page=person&view=detail&id=". $row['ID'] . ">view</a>  <a href=" . "admin.php?page=person&view=edit&id=". $row['ID'] . ">update</a></td>";
		echo "</tr>";
	}
	
	echo "</table>";

	// end display person list table
	// end search box code
}

// start dispay single person if view=detail
if(isset($_GET['view']) && $_GET['view']=="detail"){

	$sql_detail = mysql_query(
	"SELECT 
	person.ID, 
	personGender.name, 
	personGender.code, 
	person.firstName, 
	person.middleName, 
	person.lastName, 
	person.dob, 
	person.height, 
	countries.name
	FROM person
	JOIN personGender
	ON person.genderID=personGender.ID
	JOIN countries
	ON person.countryID=countries.id
	WHERE person.ID='$personID'
	");

	// start display person list table


	// results displayed  as table - table head
	echo '<table id="sortable" class="bordered">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>ID</th>';
	echo '<th>Gender</th>';
	echo '<th>First</th>';
	echo '<th>Middle</th>';
	echo '<th>Last</th>';
	echo '<th>DOB</th>';
	echo '<th>Height</th>';
	echo '<th>Country</th>';
	echo '<th>Update</th>';
	echo '</tr>';
	echo '</thead>';

	while($row = mysql_fetch_array($sql_detail)) {

		echo "<tr>";
		echo "<td>" . $row['ID'] . "</td>"; // person ID
		echo "<td>" . $row['1'] . "</td>"; // gender name
		echo "<td>" . $row['3'] . "</td>"; // first name
		echo "<td>" . $row['4'] . "</td>"; // middle name
		echo "<td>" . $row['5'] . "</td>"; // last name
		echo "<td>" . $row['6'] . "</td>"; // dob
		echo "<td>" . $row['7'] . "</td>"; // height
		echo "<td>" . $row['8'] . "</td>"; // country
		echo "<td align='center'><a href=" . "admin.php?page=person&view=edit&id=". $row['ID'] . ">update</a></td>";
		echo "</tr>";
	}

		echo "</table>";
	// end dispay single person if view=detail
}

	// add person to database process
	// check if form button = Update 

if(isset($_POST['Update'])){

	$genderID = $_POST['genderID'];
	$firstName = $_POST['firstName'];
	$middleName = $_POST['middleName'];
	$lastName = $_POST['lastName'];
	$dob = $_POST['dob'];
	$height = $_POST['height'];
	$countryID = $_POST['countryID'];
	$modifyTimestamp = date("Y-m-d H:i:s");

	$sql_update_person = 
	"UPDATE `person` SET `genderID`='$genderID',`firstName`='$firstName',`middleName`='$middleName',
	`lastName`='$lastName',`dob`='$dob',`countryID`='$countryID',`height`='$height',
	`modifyUserID`='$session_user_id',`modifyTimestamp`='$modifyTimestamp' 
	WHERE `ID` = '$personID'
	";

	// echo $sql_update_person; // debug

	$result=mysql_query($sql_update_person);

	// if successfully updated. 

	if($result){
		echo "successfully updated $firstName $lastName";
	} else {
	echo "ERROR";
	}
}




if(isset($_GET['view']) && $_GET['view']=="list"){
	// start dispay entire list if view=list

	// pagination
	if (isset($_GET["p"])) { $page  = $_GET["p"]; } else { $page=1; }; 
	$start_from = ($page-1) * 20;

	$sql_list = mysql_query(
	"SELECT 
	person.ID, 
	personGender.name, 
	personGender.code, 
	person.firstName, 
	person.middleName, 
	person.lastName, 
	person.dob, 
	person.height, 
	countries.name
	FROM person
	JOIN personGender
	ON person.genderID=personGender.ID
	JOIN countries
	ON person.countryID=countries.id
	LIMIT $start_from, 20
	");

	// start display person list table


	// results displayed  as table - table head
	echo '<table id="sortable" class="bordered">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>ID</th>';
	echo '<th>Gender</th>';
	echo '<th>First</th>';
	echo '<th>Middle</th>';
	echo '<th>Last</th>';
	echo '<th>DOB</th>';
	echo '<th>Height</th>';
	echo '<th>Country</th>';
	echo '<th>Update</th>';
	echo '</tr>';
	echo '</thead>';

	while($row = mysql_fetch_array($sql_list)) {

		echo "<tr>";
		echo "<td>" . $row['ID'] . "</td>"; // person ID
		echo "<td>" . $row['1'] . "</td>"; // gender name
		echo "<td>" . $row['3'] . "</td>"; // first name
		echo "<td>" . $row['4'] . "</td>"; // middle name
		echo "<td>" . $row['5'] . "</td>"; // last name
		echo "<td>" . $row['6'] . "</td>"; // dob
		echo "<td>" . $row['7'] . "</td>"; // height
		echo "<td>" . $row['8'] . "</td>"; // country
		echo "<td align='center'><a href=" . "admin.php?page=person&view=detail&id=". $row['ID'] . ">view</a>  <a href=" . "admin.php?page=person&view=edit&id=". $row['ID'] . ">update</a></td>";
		echo "</tr>";
	}

	echo "</table>";

	$sql = "SELECT COUNT(ID) FROM person"; 
	$rs_result = mysql_query($sql); 
	$row = mysql_fetch_row($rs_result); 
	$total_records = $row[0]; 
	$total_pages = ceil($total_records / 20); 
		  
	for ($i=1; $i<=$total_pages; $i++) {
		echo "<a href='admin.php?person&view=list&p=".$i."'>".$i."</a> ";
	} 
	// end dispay entire list if person=list
}

?>

<br>
<br>
<?php 
// start Add person process
// start dispay input form if person=add
if(isset($_GET['view']) && $_GET['view']=="add"){ ?>

	<h2>Add new person</h2>
	<form name="form1" method="post" action="<?=$PHP_SELF?>" class='ajax'>
	<select name="genderID"><?php person_gender_dropdown() ?></select>
	<input type="text" name="firstName" placeholder="First name">
	<input type="text" name="middleName" placeholder="Middle name">
	<input type="text" name="lastName" placeholder="Last name">
	<input type="date" name="dob" placeholder="Date of birth">

	<input type="number" name="height" placeholder="Height">
	<select name="countryID"><?php country_dropDown() ?></select><br>
	<input type="checkbox" name="j" value="j">Jockey
	<input type="checkbox" name="ht" value="ht">Horse Trainer
	<input type="checkbox" name="ho" value="ho">Horse Owner
	<input type="submit" name="add_person" value="Add">
	</form>

	<?php 
} 
// end dispay input form if person=add


// start hide/show last 5 added persons

if(isset($_GET['history'])){
	echo "<hr>";
	echo "<h2>Last 5 persons added</h2>";

	$sql_list = mysql_query(
	"SELECT 
	person.ID, 
	personGender.name, 
	personGender.code, 
	person.firstName, 
	person.middleName, 
	person.lastName, 
	person.dob, 
	person.height, 
	countries.name,
	person.createTimestamp
	FROM person
	JOIN personGender
	ON person.genderID=personGender.ID
	JOIN countries
	ON person.countryID=countries.id
	ORDER BY person.createTimestamp DESC 
	LIMIT 5
	");

	// start display person list table


	// results displayed  as table - table head
	echo '<table class="bordered">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>ID</th>';
	echo '<th>Gender</th>';
	echo '<th>First</th>';
	echo '<th>Middle</th>';
	echo '<th>Last</th>';
	echo '<th>DOB</th>';
	echo '<th>Height</th>';
	echo '<th>Country</th>';
	echo '<th>Created</th>';
	echo '</tr>';
	echo '</thead>';

	while($row = mysql_fetch_array($sql_list)) {

		echo "<tr>";
		echo "<td>" . $row['ID'] . "</td>"; // person ID
		echo "<td>" . $row['1'] . "</td>"; // gender name
		echo "<td>" . $row['3'] . "</td>"; // first name
		echo "<td>" . $row['4'] . "</td>"; // middle name
		echo "<td>" . $row['5'] . "</td>"; // last name
		echo "<td>" . $row['6'] . "</td>"; // dob
		echo "<td>" . $row['7'] . "</td>"; // height
		echo "<td>" . $row['8'] . "</td>"; // country
		echo "<td>" . $row['9'] . "</td>"; // created timestamp
		echo "</tr>";
	}

	echo "</table>";
}

// end hide/show last 5 added persons
// end add person process
// start edit person process

if(isset($_GET['view']) && $_GET['view']=="edit"){ 

	$sql_sp = "SELECT * FROM person WHERE ID = $personID ";
	$result_sp = mysql_query($sql_sp);
	$rows_sp =  mysql_fetch_assoc($result_sp);
	// print_r($rows_sp);

	$genderID = $rows_sp['genderID'];
	$firstName = $rows_sp['firstName'];
	$middleName = $rows_sp['middleName'];
	$lastName = $rows_sp['lastName'];
	$dob = $rows_sp['dob'];
	$height = $rows_sp['height'];
	$countryID = $rows_sp['countryID'];

	?>

	<h2>Edit person</h2>

	<form name="form1" method="post" action="/admin.php?page=person&view=detail&id=<?php echo $personID ?>" >
	<select name="genderID"><?php person_gender_dropdown() ?></select>
	<input type="text" name="firstName" placeholder="First name" value="<?php echo $firstName ?>">
	<input type="text" name="middleName" placeholder="Middle name" value="<?php echo $middleName ?>">
	<input type="text" name="lastName" placeholder="Last name" value="<?php echo $lastName ?>">
	<input type="date" name="dob" placeholder="Date of birth" value="<?php echo $dob ?>">

	<input type="number" name="height" placeholder="Height" value="<?php echo $height ?>">
	<select name="countryID"><?php country_dropDown() ?></select>
	<input type="submit" name="update_person" value="Update">
	</form>

	<?php 
} 
?>