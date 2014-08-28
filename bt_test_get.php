<?php include 'core/init.php';

$bt_where = "bt.userID = 1";
$result = bt_table($bt_where);

while($row = mysql_fetch_assoc($result)) {
	$vars[] = $row;
}

echo '{"bt":'.json_encode($vars).'}';
?>