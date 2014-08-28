<?php
$sql = "SELECT * FROM topMenu WHERE ID='5' OR ID='8' OR parent='8'";
$result = mysql_query($sql);

echo "
<nav>
  <ul>
";


while($row = mysql_fetch_array($result)) {

echo "<li><a href=" . "" . $row['link'] . ">" . $row['1'] . "</a></li>";
}

echo "</ul>
</nav>";
?>
<br><br>