<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Add Bookie</h1>
<?php include 'includes/admin-horse-menu.php'; ?>

<?php 
$name = $_POST['name'];

$sql = "INSERT INTO bookies (name) VALUES ('$name')";
$result=mysql_query($sql);

echo $result;

// if successfully updated. 
if($result){
echo "Successful";
echo "<BR>";
echo "<a href='view-bookies.php'>View result</a>";

}

else {
echo "ERROR";

}
?>




<?php include 'includes/overall/footer.php'; ?>