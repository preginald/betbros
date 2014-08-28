<?php
$connect_error = 'Sorry, we\'re experiencing connection problems.';
mysql_connect('localhost', 'yourpcma_betbros', 'Revelation1') or die($connect_error);
mysql_select_db('yourpcma_betbros') or die($connect_error);
?>