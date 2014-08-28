<?php 

include 'core/init.php';

$mID = $_GET['mID'];
$eID = $fixtureID = $_GET['fixtureID'];
$sID = 2;


$selsql = "SELECT * FROM `selection` WHERE marketID = $mID";
$selresult = mysql_query($selsql);

while ($sel = mysql_fetch_assoc($selresult)) {
	$selID = $sel['ID'];
	$selname = $sel['name'];
	?>
	<div class="col-md-3">
		<div class="fmcell"><button type="button" class="btn btn-default odds-modal" data-toggle="modal" data-target="#odds-modal" eid="<?= $eID ?>" mid="<?= $mID ?>" selid="<?= $selID ?>" selname="<?= $selname ?>"><?= $selname ?></button></div>
		<div class="fmcell"><?php //include 'fixture_markets_add.php'; ?>
		<?php 
		$sql = "SELECT DISTINCT o.odds o, b.name b, ubk.ID bkID 
			FROM odds o 
			INNER JOIN userBookies ubk
			ON o.bookieID = ubk.ID
			INNER JOIN bookies b 
			ON ubk.bookieID = b.ID 
			WHERE o.sID = $sID AND o.eventID = $eID AND o.marketID = $mID 
			AND o.selectionID = $selID";
		$result = mysql_query($sql);
		echo '<ul>';
		while ($row = mysql_fetch_assoc($result)) {?>
			<li><a class="odds-modal" href="#" data-toggle="modal" data-target="#odds-modal" eID="<?= $eID ?>" mID="<?= $mID ?>" selID="<?= $selID ?>" odds="<?= $row['o'] ?>" ubkID="<?= $row['bkID'] ?>" b="<?= $row['b'] ?>" ><?= $row['o'] ?> @ <?= $row['b'] ?></a></li>
		<?php
		}
		echo '</ul>';


		?>

		</div>
	</div>
	<?php
}?>


<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="odds-modal">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
      		<div class="modal-header">
      			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      			<h4 class="modal-title" id="modal-title">Modal title</h4>
      		</div>
 			<div class="modal-body">

 			
	 				
				<div class="row">
					<div class="col-md-2">
						<input type="number" class="form-control" id="odds" step="any" placeholder="odds" >
					</div>
				 	<div class="col-md-3">
				 		<select class="form-control " id="bookieID" required> <?php bookie_dropdown(0,$session_user_id) ?></select>
					</div>
					<div class="col-md-2">
						<select class="form-control" id="lID" required> <?php labels_dropdown(0,$session_user_id) ?></select>
					</div>
					<div class="col-md-2">
					    <div class="input-group">
						   	<span class="input-group-addon">$</span>
						    <input type="number" class="form-control ar" id="stake" placeholder="Stake">
						</div>   			
					</div>
					<div class="col-md-3">
						<div class="input-group">
							<span class="input-group-addon">$</span>
							<input type="number" class="form-control ar" id="return" placeholder="To Return" readonly="true">
						</div>
					</div>
				</div>

				<input type="hidden" id="eventID" value="<?php echo $fixtureID ?>">
				<input type="hidden" id="mID" value="<?php echo $mID ?>">
				<input type="hidden" id="selID" value="<?php echo $selID ?>">
      		</div>


			<div class="modal-footer">
			<ul class="list-inline">
				<li><button class="btn btn-default btn-odds" >Odds</button></li>
				<li><button class="btn btn-success btn-select" >Select</button></li>
				<li><button class="btn btn-info btn-bet" >Bet</button></li>
			<ul>
			</div>
		</div>
	</div>
</div>

<script>
var $btn_odds = $('.btn-odds');
var $btn_select = $('.btn-select');
var $btn_bet = $('.btn-bet');
var $modalClose = $('.close');

function hideButtons(){
	$btn_odds.hide();
	$btn_select.hide();
	$btn_bet.hide();	
}

$('#odds-modal').on('show.bs.modal', function () {
	hideButtons();
});

$odds = $('#odds');
$bookieID = $('#bookieID');
$lID = $('#lID');
$stake = $('#stake');
$toReturn = $('#return');

$bookieID.on('change',function(){
	var bookieID = $bookieID.val();
	var odds = $odds.val();

	if (odds > 0 && bookieID != '' ) {
		$btn_odds.show();
	} else {
		$btn_odds.hide();
	};
});

$lID.on('change',function(){
	var bookieID = $bookieID.val();
	var odds = $odds.val();
	var lID = $lID.val();

	if (odds > 0 && bookieID != '' && lID != '' ) {
		$btn_select.show();
	} else {
		$btn_select.hide();
	};
});

$stake.on('change keyup',function(){
	var bookieID = $bookieID.val();
	var odds = $odds.val();
	var lID = $lID.val();
	var stake = $stake.val();
	var toReturn = odds * stake;


	if ( odds > 0 && bookieID != '' && lID != '' && stake > 0 ) {
		$toReturn.val(toReturn.toFixed(2));
		$btn_bet.show();
	} else {
		$toReturn.val('');
		$btn_bet.hide();
	};

});

</script>