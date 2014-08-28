<?php
// $sql = "SELECT * FROM topMenu WHERE ID='5' OR ID='8' OR parent='8'";
$sql = "SELECT * FROM sports";
$result = mysql_query($sql);

echo "
<nav>
  <ul>
";


while($fixturesMenu = mysql_fetch_array($result)) {

echo "<li><a href=" . "" . $fixturesMenu['link'] . ">" . $fixturesMenu['1'] . "</a></li>";
}

echo "</ul>
</nav>";
?>
<br><br>