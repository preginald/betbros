<?php
protect_page(); 

$sportsID = mysql_real_escape_string($_GET['sid']);
$eventSeasonID = mysql_escape_string($_GET['esid']);
$fixtureID = mysql_escape_string($_GET['fixid']);

?>