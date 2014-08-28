<?php 
protect_page(); 

$sportsID = mysql_real_escape_string($_GET['sid']);

$sql = "SELECT * FROM `sports` WHERE 1";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result)) {

	$ID = $row['ID'];
	$name = $row['name'];
	$page = "fix-$ID.php";

	if(isset($_GET['sid']) && $_GET['sid']==$ID) {

		include "$page"; 
	}
}

?>