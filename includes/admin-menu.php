<?php

$sqlAdminMenu = "SELECT * FROM topMenu WHERE parent='5' AND enable='1'";

$resultAdminMenu = mysql_query($sqlAdminMenu);



echo "

<nav>

	<ul>

";





while($adminMenu = mysql_fetch_array($resultAdminMenu)) {



echo "<li><a href=" . "" . $adminMenu['link'] . ">" . $adminMenu['1'] . "</a></li>";

}



echo "</ul>

</nav>";

?>