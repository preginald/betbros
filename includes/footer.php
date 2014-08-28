<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
?>
<nav class="navbar navbar-default navbar-fixed-bottom hidden-xs " role="navigation">
	<div class="container">
		<ul class="nav navbar-nav">
			<li><p class="navbar-text">&copy; Peter Reginald 2014.</p></li>
		</ul>
		<ul class="nav navbar-nav navbar-right ">
			<li><a href="blog">Blog</a></li>
			<li><a href="index.php?page=release-notes&view=default">0.1.<?= version(); ?></a></li>
			<li><p class="navbar-text"><?php echo ''.$total_time.' s';?></p></li>
		</ul>
	</div>
</nav>