<?php 

include 'core/init.php'; 
$fixtureID = $_GET['fixtureID'];

?>


<div class="panel-body" id="fxmkbox<?= $fixtureID ?>">
<!-- Nav tabs -->
<ul class="nav nav-tabs">

		
<?php
$mksql = "SELECT * FROM `markets` WHERE sportID = 2";
$mkresult = mysql_query($mksql);

while ($mk = mysql_fetch_assoc($mkresult)) {
	$mID = $mk['ID'];
	$mkname = $mk['abbr'];
	?>
	<li><a class="mselect" href="#mID_<?= $mID ?><?= $fixtureID ?>" data-toggle="tab" mID="<?= $mID ?>" fID="<?= $fixtureID ?>"><?= $mkname ?></a></li>
	<?php
}?>
</ul>

	<!-- Tab panes -->
	<div class="tab-content">
	<?php
	$mksql = "SELECT * FROM `markets` WHERE sportID = 2";
	$mkresult = mysql_query($mksql);
	while ($mk = mysql_fetch_assoc($mkresult)) {
		$mID = $mk['ID'];
		$mkname = $mk['abbr'];
		?>
		<div class="tab-pane" id="mID_<?= $mID ?><?= $fixtureID ?>"></div>
		<?php
	}?>
	</div>
</div>