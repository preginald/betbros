<?php
include 'core/init.php';

$option = $_GET['option'];
$where = "bt.userID = $session_user_id";

if ($option == "filterBT") {
	$lID = implode(', ', $_GET['lID']);
	$where .= " AND bt.labelID IN ( $lID ) ";
}

$btStat = get_bt_stats($where); 
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-3"><strong>Tot Stake:</strong> $<?= $btStat['tStake'] ?></div>
			<div class="col-xs-3"><strong>Returns:</strong> $<?= $btStat['gReturns'] ?></div>
			<div class="col-xs-3"><strong>Profit:</strong> $<?= $btStat['gPL'] ?></div>
			<div class="col-xs-3"><strong>Avg Profit:</strong> $<?= $btStat['avgPL'] ?></div>
		</div>
	</div>
  	<div class="panel-body">
		<div class="row">
			<div class="col-xs-3"><strong>Margin:</strong> <?= $btStat['gPLm'] ?>%</div>
			<div class="col-xs-3"><strong>Win Rate:</strong> <?= $btStat['sRate'] ?>%</div>
			<div class="col-xs-3"><strong>Avg Odds:</strong> <?= $btStat['avgodds'] ?></div>
			<div class="col-xs-3"><strong>Min Odds:</strong> <?= $btStat['minodds'] ?></div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-xs-3"><strong>Total Bets:</strong> <?= $btStat['tBets'] ?></div>
			<div class="col-xs-2"><strong>Lose:</strong> <?= $btStat['tBetsL'] ?></div>
			<div class="col-xs-2"><strong>Win:</strong> <?= $btStat['tBetsW'] ?></div>
			<div class="col-xs-2"><strong>Pending:</strong> <?= $btStat['tBetsPn'] ?></div>
			<div class="col-xs-2"><strong>Push:</strong> <?= $btStat['tBetsPu'] ?></div>
		</div>		
	</div>
	</div>
</div>