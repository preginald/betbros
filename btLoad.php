<?php
include 'core/init.php';
// procedure to draw Bet Tracker table
$rPL = '';
$option = $_GET['option'];

$bt_where = "bt.userID = $session_user_id";


if ($option == "filterBT") {
	$lID = implode(', ', $_GET['lID']);
	$bt_where .= " AND bt.labelID IN ( $lID ) ";
}

$result_bt = bt_table($bt_where);

$gReturns = 0;
while($row = mysql_fetch_assoc($result_bt)) {
	$bstID = $row['bstID'];
	$stake = number_format($row['stake'],2);
	$odds = $row['odds'];
	$returns = number_format($row['returns'],2);
	$bkname =$row['bkname'];
	$blname =$row['blname'];
	$lID = $btlID =$row['btlID'];
	$btlname =$row['btlname'];

	$date = $row['date'];
	$time = $row['time'];

	$bstname = $row['bstname'];

	$PL = number_format($row['PL'],2);
	$ROI = number_format(($PL / $stake)*100,1);
	$gReturns += number_format($returns,2);
	
	$rPL = number_format($rPL + $PL,2);

	$btID = $row['btID'];

	$class = get_betStatus_class_v2($bstID);
	?>

	<li class="bt-item" id="<?= $btID ?>">
		<div class="panel-group" >
			<div class="panel panel-<?= $class ?>">

				<div class="panel-heading">
					<div class="row">	
						<div class="col-md-9">
							<div class="row">
								<div class="col-xs-2"><?= $bstname ?></div>
								<div class="col-xs-2">$<?=$stake ?></div>
								<div class="col-xs-2">$<?= $returns ?></div>
								<div class="col-xs-2">$<?= $PL ?></div>
								<div class="col-xs-2"><?= $ROI?>%</div>
								<div class="col-xs-2">$<?= $rPL ?></div>
							</div>
						</div>
					</div>
				</div>

				<div class="panel-body">
	    			<?= bd_bt_inner($btID) ?>
	  			</div>

	  			<div class="panel-footer">
					<div class="row">
						<div class="col-xs-7 col-md-9">
							<ul class="list-inline">
								<li><?= $btID ?></li>
								<li><?= $date ?> <?= $time ?></li>
							</ul>
						</div>
						<div class="col-xs-4 col-md-2"><select class="labelID form-control" > <?php labels_dropdown($lID,$session_user_id) ?></select></div>
						<div class="col-xs-1 col-md-1">
							<button type="button" class="btn btn-default btn-sm btdelbtn pull-right" ><i class="fa fa-trash-o fa-lg"></i></button>
						</div>
					</div>		
	  			</div>
	  		</div>
	  	</div>	
	</li>

	<?php
  }?>