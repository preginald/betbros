<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Add Teams</h1>
<?php include 'includes/sports-admin-menu.php'; ?>
<br><br>

<?php 
$name = $_POST['name'];
$countryID = $_POST['countryID'];
$sportID = $_POST['sportsID'];
$eventID = $_POST['eventID'];


$sql = "INSERT INTO teams (name, countryID, sportID, eventID) VALUES ('$name','$countryID','$sportID', '$eventID')";
$result=mysql_query($sql);

echo $result;

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