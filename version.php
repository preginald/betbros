<?php 

$newversion = 1 + version();

if(isset($_GET['addversion']) && $_GET['addversion']=="1"){
	echo '<div>';
	echo '<input type="text" name="vnumber" size="1" readonly value="'.$newversion.'">';
	echo '<input type="text" name="vnote" size="60" required placeholder="Version summary">';

	echo '<br/>';
	echo '<textarea rows="4" cols="55" name="vdetails" placeholder="Version Details"></textarea>';
	echo '<br/>';
	echo '</div>';
}

?>