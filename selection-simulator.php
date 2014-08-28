<h1>Selection Simulator</h1>

<div class="btn-group" id="labels"></div>

<div id="selection-form"></div>

<div class="hidden" id="dash">
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
				<div class="col-md-3">Margin: <span id="gPLm"></span>%</div>
				<div class="col-md-3">Win Rate: <span id="sRate"></span>%</div>
				<div class="col-md-3">Avg Odds: <span id="avgOdds"></span></div>
				<div class="col-md-3">Min Odds: <span id="minOdds"></span></div>
			</div>
		</div>
		<div class="panel-footer">
			<div class="row">
				<div class="col-md-3">Total Bets: <span id="tBets"></span></div>
				<div class="col-md-2">Lose: <span id="tBetsL"></span></div>
				<div class="col-md-2">Win: <span id="tBetsW"></span></div>
				<div class="col-md-2">Pending: <span id="tBetsPn"></span></div>
				<div class="col-md-2">Push: <span id="tBetsPu"></span></div>
			</div>		
		</div>
		</div>
	</div>	
</div>

<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th class="text-right">#</th>
				<th>Event</th>
				<th>Selection</th>
				<th>Status</th>
				<th class="text-right">Stake</th>
				<th class="text-right">Return</th>
				<th class="text-right">PL</th>
				<th class="text-right">ROI</th>
				<th class="text-right">Bank</th>
			</tr>
		</thead>
  		<tbody id="selections"></tbody>
  </table>
</div>



<script type="text/javascript" src="js/simulator.js"></script>