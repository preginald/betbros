<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Edit Horse</h1>
<?php include 'includes/admin-horse-menu.php';

// get value from edit-horses.php
$name = $_POST['name'];
$birth = $_POST['birth'];
$sexID = $_POST['sexID'];
$horseOwnerID = $_POST['horseOwnerID'];
$horseTrainerID = $_POST['horseTrainerID'];
$countryID = $_POST['countryID'];
$id = $_POST['id'];

// update data in mysql database 
$sql="UPDATE horses SET name='$name', birth='$birth', genderID='$sexID', 
OwnerID='$horseOwnerID', TrainerID='$horseTrainerID', CountryID='$countryID' WHERE ID='$id'";
$result=mysql_query($sql);

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