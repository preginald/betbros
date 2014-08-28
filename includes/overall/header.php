<!-- Start overall header -->
<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
?>
<?php // include 'includes/getpage.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<body>
	<?php include 'includes/header.php'; ?>
		<div class="container-fluid">
        	<div class="row">
        		<div id="main-content"></div>
<!-- End overall header -->