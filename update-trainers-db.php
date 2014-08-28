<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Edit Horse Trainer</h1>
<?php include 'includes/admin-horse-menu.php';

// get value from edit-sports.php
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$countryID = $_POST['countryID'];
$id = $_POST['id'];

// update data in mysql database 
$sql="UPDATE horseTrainer SET firstName='$firstName',  lastName='$lastName',countryID='$countryID' WHERE ID='$id'";
$result=mysql_query($sql);

// if successfully updated. 
if($result){
echo "Successful";
echo "<BR>";
echo "<a href='view-trainers.php'>View result</a>";

}

else {
echo "ERROR";
}

?>

<?php include 'includes/overall/footer.php'; ?>