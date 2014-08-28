<?php require_once 'core/init.php'; ?>
<?php 
$sbID = sanitize($_GET['sbID']);
//print_r_pre($_POST);
$sel = $mk = 0;
$sbType = 2;


//echo $session_user_id;
$sb_where = "sb.ID = $sbID";
$sb_order = "sb.ID ASC";

$result = sb_table($sb_where, $sb_order);


?>

<?php
$selectionCounter =0;
while($row = mysql_fetch_array($result)) {

	$mysqldate = $row['date'];
	$audate = date('D d-M', strtotime($mysqldate));
	$mysqltime = $row['time'];
	$time = date('g:i a', strtotime($mysqltime));
	$sID = $row['sID'];
	$htid = $row['ts1id'];
	$atid = $row['ts2id'];
	$fixtureID = $row['fixID'];
	$fixst = $row['fixstID'];
	//$sbID = $row['sbID'];
	//$uname = $row['user'];

	$mID = $row['mID'];
	$mname = $row['mname'];
	$selID = $row['selID'];
	$sel = selection_display($mID,$selID,$t1name,$t2name);

	$lID = $row['lID'];
	
	$eventSeasonID = $esID = $row['esID'];

	$bt_count = bt_count($fixtureID,$session_user_id);
	$sb_count = sb_count($fixtureID,$session_user_id);

	$t1name = $row['t1name'];
	$t2name = $row['t2name'];

	$odds_bkID = get_odds_bookieID(2,$fixtureID,$mID,$selID);
	$odds_val = $odds_bkID['o'];
	$bkID_val = $odds_bkID['bkID'];

			?>
<li id="sb_a_<?= $sbID ?>">
<div class="panel-group" >
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-10">
					<ul class="list-inline">
						<li>
							<select name="labelID" class="form-control" id="labelID"> <?php labels_dropdown($lID,$session_user_id) ?></select>
						</li>
						<!--<li><?= $audate . " " . $time ?></li>-->
						<li>
							<a href="index.php?page=teams&view=detail&sid=<?php echo $sportsID;?>&esid=<?php echo $eventSeasonID ?>&tsid=<?php echo $htid ?>"><?php echo $t1name ?></a>
							<a href="index.php?page=fixtures&view=detail&sid=<?php echo $sportsID;?>&esid=<?php echo $eventSeasonID ?>&fixid=<?php echo $fixtureID . fixture_home_away_url($fixtureID) . score_url() ?> ">
							<?= matchStatus_v2($fixst,$fixtureID) ?></a>
							<a href="index.php?page=teams&view=detail&sid=<?php echo $sportsID;?>&esid=<?php echo $eventSeasonID ?>&tsid=<?php echo $atid ?>"><?php echo $t2name ?></a>  
						</li>
							<li><?= $mname ?></li>
							<li><?= $sel ?></li>
							<li><?= betStatus(bd_status_checker($sbType,$fixtureID,$fixst,$mID,$selID)) ?></li>
					</ul>
				</div>
				<div class="col-md-2">
					<ul class="list-inline pull-right">
							<li><button type="button" class="btn btn-default btn-sm rmanalbtn" data-sbid="<?= $sbID ?>"><i class="fa fa-times fa-lg"></i></button></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="panel-footer">
			<div class="row">
				
				<ul class="list-inline">
					<div class="col-md-3">
						<li>
  							<div class="form-group">
						    	<label for="bookieID">Bookie</label>
						    	<select class="form-control" id="bookieID"> <?php bookie_dropdown($bkID_val,$session_user_id) ?></select>
						  </div>														
						</li>
					</div>
					<div class="col-md-2">
						<li>
							<div class="form-group">
						    	<label for="odds">Odds</label>
						    	<input type="number" class="form-control ar" id="odds" placeholder="Odds" value="<?= $odds_val ?>">
						  </div>							
						</li>
					</div>
					<div class="col-md-3">
						<li>
						  <div class="form-group">
						    <label for="stake">Stake</label>
						    <div class="input-group">
							   	<span class="input-group-addon">$</span>
							    <input type="number" class="form-control ar" id="stake" placeholder="Stake">
							</div>    
						  </div>							
						</li>
					</div>
					<div class="col-md-3">
						<li>
							<div class="form-group">
						    	<label for="return">To Return</label>
						    	<div class="input-group">
							    	<span class="input-group-addon">$</span>
							    	<input type="number" class="form-control ar" id="return" placeholder="To Return" readonly="true">
						    	</div>
						  </div>							
						</li>						
					</div>
				</ul>
				
			</div>
		</div>
	</div>
</div>			
</li>
<input type="hidden" id="sbID02" value="<?= $sbID ?>">
<input type="hidden" id="fixID" value="<?= $fixtureID ?>">
<input type="hidden" id="selID" value="<?= $selID ?>">
<input type="hidden" id="mID" value="<?= $mID ?>">
					
<?php
}?>