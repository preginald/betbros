<?php

// include 'core/init.php';
// admin_protect();
// include 'includes/overall/header.php';

protect_page();
$userID = $session_user_id; 

echo "<h1>View Sports</h1>";

$sql = "SELECT * FROM sports";

$result = mysql_query($sql);

echo "

<nav>

	<ul>

";

while($row = mysql_fetch_array($result)) {


echo "<li><a href=" . "index.php?page=" . $_GET['page'] . "&id=" . $row['ID'] . ">" . $row['1'] . "</a></li>";

}



echo "</ul></nav><br><br>";




// Check if sports ID exists in url.

if(isset($_GET['id']) && $_GET['id']=="1") {

  include 'racecards.php';
  // echo "racecards selected"; // debugging

} elseif (isset($_GET['id']) && $_GET['id']=="2") {

  // echo "football selected"; // debugging
  include 'football.php';  

} else {

  // If sports ID is not set from url then display message

  echo "<h2>Select a sport from the menu</h2>";

}

// If sports ID exists in url then load that sport's fixtures.



// include 'selection-board-table.php';

// include 'bet-tracker-table.php';




?>