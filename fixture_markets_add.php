<!-- Button trigger modal -->

<button class="btn btn-primary btn-sm" showodds="<?= $fixtureID ?><?= $mID ?><?= $selID ?>" >
	add
</button>


<?php echo get_odds_bookie_list(2,$fixtureID,$mID,$selID); ?>


<div class="add-odds" id="odds-<?= $fixtureID ?><?= $mID ?><?= $selID ?>">

	<div class="modal-body">

	    <input type="number" class="form-control col-md-3" id="oddsval-<?= $fixtureID ?><?= $mID ?><?= $selID ?>" step="any" value="" placeholder="odds" >  

		<select select class="form-control col-md-3" id="bookieIDval-<?= $fixtureID ?><?= $mID ?><?= $selID ?>" required> <?php bookie_dropdown(0,$session_user_id) ?></select>
		<select select class="form-control col-md-3" id="lIDval-<?= $fixtureID ?><?= $mID ?><?= $selID ?>" required> <?php labels_dropdown(0,$session_user_id) ?></select>
	</div>

	<input type="hidden" id="eventID-<?= $fixtureID ?><?= $mID ?><?= $selID ?>" value="<?php echo $fixtureID ?>">
	<input type="hidden" id="mID-<?= $fixtureID ?><?= $mID ?><?= $selID ?>" value="<?php echo $mID ?>">
	<input type="hidden" id="selID-<?= $fixtureID ?><?= $mID ?><?= $selID ?>" value="<?php echo $selID ?>">

	<div class="modal-footer">
		<button class="btn btn-info btn-odds" odds-id="<?= $fixtureID ?><?= $mID ?><?= $selID ?>">Odds</button>
		<button class="btn btn-success" select-id="<?= $fixtureID ?><?= $mID ?><?= $selID ?>">Select</button>
	</div>
</div>