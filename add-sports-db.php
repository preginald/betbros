<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Add Sport to list</h1>
<?php include 'includes/sports-admin-menu.php'; ?>
<br><br>

<?php 
$name = $_POST['name'];

$sql = "INSERT INTO sports (name) VALUES ('$name')";
$result=mysql_query($sql);

echo $result;

// if successfully updated. 
if($result){
echo "Successful";
echo "<BR>";
echo "<a href='view-sports.php'>View result</a>";

}

else {
echo "ERROR";

}
?>




<?php include 'includes/overall/footer.php'; ?>