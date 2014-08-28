<?php 
if (isset($_GET['action']) && $_GET['action']=="odds" && $sbID==$_GET['id']) {
	?>
	<div class="r">
	<form name="add-odds" method="post" action="<?php echo $URL = str_replace("&action=odds&id=$sbID", "", $bt_url); ?>">
	<input type="number" class="w70 ar" name="odds" step="any" value="<?php echo show_odds_bookie($sID,$fixtureID,$mID,$selID) ?>">
	<select name="bookieID" required> <?php bookie_dropdown(0,$session_user_id) ?></select>
	<input type="hidden" name="eventID" value="<?php echo $fixtureID ?>">
	<input type="hidden" name="mID" value="<?php echo $mID ?>">
	<input type="hidden" name="selID" value="<?php echo $selID ?>">
	<input type="hidden" name="sID" value="<?php echo $sID ?>">
	<input type="hidden" name="fixID" value="<?php echo $fixtureID ?>">
	<input type="submit" name="add_odds" value="Add">
	</form>
	</div>
	<?php
} else {?>
	<a href="<?= $bt_url ?>&action=odds&id=<?= $sbID ?>"><?= show_odds_bookie($sID,$fixtureID,$mID,$selID)?></a>
	<?php
} 
?>