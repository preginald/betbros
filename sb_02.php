<?php require_once 'core/init.php'; ?>
<?php 
//print_r_pre($_POST);
$sel = $mk = 0;
$sbType = 2;

//echo $session_user_id;
$sb_where = "userID = $session_user_id AND sb.active = 1 AND sb.analyse != 1 ";
$sb_order = "fix.date, fix.time ASC";

$result = sb_table($sb_where, $sb_order);

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
	<li class="sb-item" id="<?= $sbID ?>">
	<div class="panel-group" >
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-6">
						<ul class="list-inline">
							<!--<li><?= $audate . " " . $time ?></li>-->
							<li><a href="index.php?page=teams&view=detail&sid=<?php echo $sportsID;?>&esid=<?php echo $eventSeasonID ?>&tsid=<?php echo $htid ?>"><?php echo $t1name ?></a></li>
							<li><?= matchStatus_v2($fixst,$fixtureID) ?></li>
							<li><a href="index.php?page=teams&view=detail&sid=<?php echo $sportsID;?>&esid=<?php echo $eventSeasonID ?>&tsid=<?php echo $atid ?>"><?php echo $t2name ?></a></li>
							</li>
								<li><?= $mabbr ?></li>
								<li><?= $sel ?></li>
								<li><?= betStatus(bd_status_checker($sbType,$fixtureID,$fixst,$mID,$selID)) ?></li>
						</ul>
					</div>
					<div class="col-md-6">
						<ul class="list-inline pull-right">
							<li>
								<select class="labelID form-control" > <?php labels_dropdown($lID,$session_user_id) ?></select>
							</li>
							<li><button type="button" class="btn btn-default btn-sm" ><i class="fa fa-comments-o fa-lg"></i></button></li>
							<li>
								<button type="button" class="btn btn-default btn-sm anabtn" data-sbid="<?= $sbID ?>">
								<i class="fa fa-star fa-lg"></i>
								</button>							
							</li>
							<li>
								<button type="button" class="btn btn-default btn-sm fxmkbtn" data-toggle="collapse" data-target="#markets_<?= $fixtureID ?>" data-fixID="<?= $fixtureID ?>">
								<i class="fa fa-paw fa-lg"></i>
								</button>
							</li>
							<li><button type="button" class="btn btn-default btn-sm seldelbtn" data-sbid="<?= $sbID ?>"><i class="fa fa-trash-o fa-lg"></i></button></li>
						</ul>
					</div>
				</div>
			</div>


			<div id="markets_<?= $fixtureID ?>" class="collapse">
	        		<?php // include 'fixture_selections.php'; ?>
	    	</div>

			<div id="sel_<?= $fixtureID ?>" class="collapse">
	        		<?php // include 'fixture_selections.php'; ?>
	    	</div>

			<div class="panel-footer">
				<div class="row">
					<div class="col-md-12">
							<ul class="list-inline">
							<li><?= $fixtureID ?></li>
							<li><?= $uname ?></li>
							<li><?php echo ($sb_count == 0) ? $sb_count . '/' . sb_count($fixtureID,0) : '<a href="' . URL_replace($url,'sbff',$fixtureID) . '">' . $sb_count . '</a>/' . sb_count($fixtureID,0); ?></li>
							<li><?php echo ($bt_count == 0) ? $bt_count . '/' . bt_count($fixtureID,0) : '<a href="' . URL_replace($url,'bdff',$fixtureID) . '">' . $bt_count . '</a>/' . bt_count($fixtureID,0); ?></li>
							<li><?= show_odds_bookie($sbType,$fixtureID,$mID,$selID) ?></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>			
	</li>
						
	<?php
}?>