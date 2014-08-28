<?php 

$curPage=curPageURLshort();

// get values from url
$sportsID = mysql_real_escape_string($_GET['sid']); 
$activeID = mysql_real_escape_string($_GET['active']); 
$activeID = 2;

if (isset($_GET['esid'])) {
	$eventSeasonID = mysql_real_escape_string($_GET['esid']);
}


$this_week_start = date('Y-m-d', strtotime('this week')); 
$this_week_finish = date('Y-m-d', strtotime('next week'));

if($activeID==1){
	$where="WHERE s.ID = '$sportsID' AND es.startDate <= CURDATE() AND es.endDate >= CURDATE()";
	$headingActive="Current";
}
if($activeID==2){
	$where="WHERE s.ID = '$sportsID'";
	$headingActive="All";
}?>
<ul>
<?php

$scountry = "SELECT DISTINCT c.id cID, c.name c FROM `events` e INNER JOIN countries c ON e.countryID = c.id WHERE e.sportID = 2 ORDER BY c.name";

$rcountry = mysql_query($scountry);
while ($country = mysql_fetch_assoc($rcountry)) {?>
	<a href="#"><li data-cID="<?= $country['cID']; ?>"><?= $country['c']; ?></li></a>
	<ul class="event" id="<?= $country['cID']; ?>">
	<?php 
	$se = "SELECT e.ID eID, e.name e from `events` e WHERE e.sportID = 2 AND e.countryID = $country[cID]";
	$re = mysql_query($se);

	while ($e = mysql_fetch_assoc($re)) {?>
		
		<li><small><?= $e['e'] ?></small></li>
	
	<?php }	?>
	</ul>
<?php } ?> 
</ul>

<script>
	$(document).ready(function(){

		$('.event').hide();




		$('li').click(function(){

			var x = $(this).attr('data-cID');

			$( '#'+x ).slideToggle( "fast", function(){});

		})
		
	});
</script>