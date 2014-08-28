<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Add Fixtures</h1>
<?php include 'includes/sports-admin-menu.php'; ?>
<br><br>

<?php 
$date = $_POST['date'];
$time = $_POST['time'];
$ID = $_POST['eventID'];
$homeTeamID = $_POST['homeTeamID'];
$awayTeamID = $_POST['awayTeamID'];
$sportID = $_POST['sportID'];

$sql = "INSERT INTO fixtures (date, time, sportID, eventID, homeTeamID, awayTeamID) VALUES ('$date','$time','$sportID','$ID', '$homeTeamID', '$awayTeamID')";
$result=mysql_query($sql);

echo $result;

// if successfully updated. 
if($result){
echo "Successful";
echo "<BR>";
echo "<a href='view-events.php'>View result</a>";

}

else {
echo "ERROR";

}
?>




<?php include 'includes/overall/footer.php'; ?>