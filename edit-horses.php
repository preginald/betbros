<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Edit Horses</h1>
<?php include 'includes/admin-horse-menu.php'; 

$id=$_GET['id']; // Gets value of ID sent from address bar

$sql = "SELECT * FROM horses WHERE ID='$id'";
$result = mysql_query($sql);
$rows=mysql_fetch_array($result);

?>

<form name="form1" method="post" action="update-horses-db.php">
<input type="text" name="name" value="<? echo $rows['name']; ?>">
<input type="number" name="birth" value="<? echo $rows['birth']; ?>">
<select name="sexID"><?php horse_sex_dropDown() ?>    </select>
<select name="horseOwnerID"><?php horse_owner_dropDown() ?></select>
<select name="horseTrainerID"><?php horse_trainer_dropDown() ?></select>
<select name="countryID"><?php country_dropDown() ?></select>
<input name="id" type="hidden" id="id" value="<? echo $id; ?>">
<input type="submit" name="Submit" value="Add">
</form>



<?php include 'includes/overall/footer.php'; ?>