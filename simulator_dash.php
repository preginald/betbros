<div class="panel panel-default">
	<div class="panel-heading">
		<div class="row">
			<div class="col-md-3">Total Stake: $<span id="tstake"></span></div>
			<div class="col-md-3">Returns: $<span id="gReturns"></span></div>
			<div class="col-md-3">Profit: $<span id="gPL"></span></div>
			<div class="col-md-3">Avg Profit: $<span id="avgPL"></span></div>
		</div>
	</div>
  	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">Margin: <?= $btStat['gPLm'] ?>%</div>
			<div class="col-md-3">Win Rate: <?= $btStat['sRate'] ?>%</div>
			<div class="col-md-3">Avg Odds: <?= $btStat['avgodds'] ?></div>
			<div class="col-md-3">Min Odds: <?= $btStat['minodds'] ?></div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-md-3">Total Bets: <?= $btStat['tBets'] ?></div>
			<div class="col-md-2">Lose: <?= $btStat['tBetsL'] ?></div>
			<div class="col-md-2">Win: <?= $btStat['tBetsW'] ?></div>
			<div class="col-md-2">Pending: <?= $btStat['tBetsPn'] ?></div>
			<div class="col-md-2">Push: <?= $btStat['tBetsPu'] ?></div>
		</div>		
	</div>
	</div>
</div>