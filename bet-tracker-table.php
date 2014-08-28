<h1>Bet Tracker</h1>
<?php 

$tBetsACCU = $twr = $tvr = $tBetsSingle = $tBetsPn = $tBetsPu = $tBetsL = $tBetsW = $tBets = $tStake = $rPL = 0;
$bt_where = "bt.userID = $session_user_id";

bt_update_s1();
bt_update_s2();
?>

<div id="bt-form"></div>

<div id="btdash" data-where="<?= $bt_where ?>"></div>

<div>
	<ul id="bt"></ul>
</div>

<script type="text/javascript" src="js/bet-tracker.js"></script>