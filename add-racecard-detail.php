<?php 
// this file is no longer userd. It has been deprecated by another process.




// check if form's submit button active
if(isset($_POST['Submit'])){
	echo 'Submit button pressed';
	// Required field names
	$required = array('stall', 'horseID', 'jockeysID');

	// Loop over field names, make sure each one exists and is not empty
	$error = false;
	foreach($required as $field) {
		if (empty($_POST[$field])) {
			$error = true;
		}
	}

	if ($error) {

		echo "All fields are required.";
		echo $_POST['stall'];

	} else {

		echo "Adding horse and jockey...";

		$stall = $_POST['stall'];
		$draw = $_POST['draw'];
		$horseID = $_POST['horseID'];
		$jockeysID = $_POST['jockeysID'];
		$weight = $_POST['weight'];

  		$sql_add_racedetail = 
  		"INSERT INTO `horseRaces`(`racecardID`, `stall`, `draw`, `horseID`, `jockeysID`, `weight`, `horseStatusID`) 
  		VALUES ('$raceID','$stall','$draw','$horseID','$jockeysID','$weight', '1')
  		";

  		$result=mysql_query($sql_add_racedetail);

  	}

  }else{

  	echo 'submit button not pressed';

  }

?>

<h2>Add race detail</h2>
<form name="add-racedetail" method="post" action="">

<input type="number" name="stall" min="0" style="width:3em" placeholder="Stall" >
<input type="number" name="draw" min="0" style="width:3em" placeholder="Draw" >
<select name="horseID"><?php horse_dropdown() ?></select>
<select name="jockeysID"><?php jockey_dropdown() ?></select>
<input type="number" name="weight" min="0" style="width:3em" placeholder="Weight" >
<input type="hidden" value="<?php echo $raceID ?>" name="racecardID" />

<input type="submit" name="Submit" value="Add">
</form>