<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Edit Sports List</h1>
<?php include 'includes/sports-admin-menu.php'; ?>
<br>
<br>
<h2>List of Sports</h2>
<?php
// get value from edit-sports.php
$name = $_POST['name'];
$id = $_POST['id'];

// update data in mysql database 
$sql="UPDATE sports SET name='$name' WHERE ID='$id'";
$result=mysql_query($sql);

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