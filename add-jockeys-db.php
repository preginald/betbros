<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Add Jockeys</h1>
<?php include 'includes/admin-menu.php'; ?>
<br><br>

<?php 
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$countryID = $_POST['countryID'];

$sql = "INSERT INTO jockeys (firstName,lastName, CountryID) 
		VALUES ('$firstName','$lastName','$countryID')";
$result=mysql_query($sql);

echo $result;

// if successfully updated. 
if($result){
echo "Successful";
echo "<BR>";
echo "<a href='view-jockeys.php'>View result</a>";

}

else {
echo "ERROR";

}
?>




<?php include 'includes/overall/footer.php'; ?>