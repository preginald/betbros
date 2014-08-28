<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Edit Teams</h1>
<?php include 'includes/admin-menu.php'; ?>
<br>
<br>
<?php
// get value from edit-sports.php
$name = $_POST['name'];
$countryID = $_POST['countryID'];
$sportID = $_POST['sportID'];
$eventID = $_POST['eventID'];
$id = $_POST['id'];

// update data in mysql database 
$sql="UPDATE teams SET name='$name', countryID='$countryID', sportID='$sportID', eventID='$eventID' WHERE ID='$id'";
$result=mysql_query($sql);

// if successfully updated. 
if($result){
echo "Successful";
echo "<BR>";
echo "<a href='view-teams.php'>View result</a>";

}

else {
echo "ERROR";
}

?>

<?php include 'includes/overall/footer.php'; ?>