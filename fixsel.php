<?php
require_once 'core/init.php'; 

$fixID = sanitize($_GET['fID']);
$sbType = 2;

$sb_where = "userID = $session_user_id AND sb.fixtureID = $fixID ";
$sb_order = "fix.date, fix.time ASC";

$result = sb_table($sb_where, $sb_order);
?>
<div class="col-md-8">
<?php
while($row = mysql_fetch_array($result)) {
	$sbID = $row['sbID'];

	$mysqldate = $row['date'];
	$audate = date('D d-M', strtotime($mysqldate));
	$mysqltime = $row['time'];
	$time = date('g:i a', strtotime($mysqltime));
	$sID = $row['sID'];
	$htid = $row['ts1id'];
	$atid = $row['ts2id'];
	$fixtureID = $row['fixID'];
	$fixst = $row['fixstID'];

	$mID = $row['mID'];
	$mname = $row['mname'];
	$mabbr = $row['mabbr'];
	$selID = $row['selID'];
	$sel = selection_display($mID,$selID,$t1name,$t2name);

	$lID = $row['lID'];
	
	$eventSeasonID = $esID = $row['esID'];

	$bt_count = bt_count($fixtureID,$session_user_id);
	$sb_count = sb_count($fixtureID,$session_user_id);

	$t1name = $row['t1name'];
	$t2name = $row['t2name'];
?>
	<ul class="list-inline">
		<li><?= $mabbr ?></li>
		<li><?= $sel ?></li>
		<li><?= show_odds_bookie($sbType,$fixtureID,$mID,$selID) ?></li>
		<li><?= betStatus(bd_status_checker($sbType,$fixtureID,$fixst,$mID,$selID)) ?></li>
	</ul>					
<?php
}?>
</div>