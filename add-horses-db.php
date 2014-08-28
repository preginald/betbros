<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Add Horse</h1>
<?php include 'includes/admin-horse-menu.php'; ?>
<br><br>

<?php 
$name = $_POST['name'];
$birth = $_POST['birth'];
$sexID = $_POST['sexID'];
$horseOwnerID = $_POST['horseOwnerID'];
$horseTrainerID = $_POST['horseTrainerID'];
$countryID = $_POST['countryID'];

$sql = "INSERT INTO horses (name,birth, genderID, OwnerID, TrainerID, CountryID) 
		VALUES ('$name','$birth','$sexID','$horseOwnerID','$horseTrainerID','$countryID')";
$result=mysql_query($sql);

echo $result;

// if successfully updated. 
if($result){
echo "Successful";
echo "<BR>";
echo "<a href='view-horses.php'>View result</a>";

}

else {
echo "ERROR";

}
?>




<?php include 'includes/overall/footer.php'; ?>