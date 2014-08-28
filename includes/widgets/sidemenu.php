<?php
if (isset($_GET['menu']) && !empty($_GET['menu'])) {
	$menuID = $_GET['menu'];
	$sql = "SELECT * FROM `topMenu` WHERE parent = $menuID";
	$result = mysql_query($sql);

	echo "<ul>";
	while ($row=mysql_fetch_assoc($result)) {
		echo '<li><a href="' . $row['link'] . '">' . $row['name'] . '</li>' ;
	}

	echo "</ul>";
}

?>