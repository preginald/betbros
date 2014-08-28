<?php 
include 'core/init.php';
//$session_user_id = $user_data['user_id'];
$fixID = sanitize($_GET['fixID']);
$result_sub = sb_table($sb_where, " sb.ID ASC ",$fixID);
echo '<div class="comments">';
while($row_sub = mysql_fetch_array($result_sub)) {
	$sbID = $row_sub['ID'];
	$sbType = $row_sub['sID'];
	$fixtureID = $row_sub['fixID'];
	$dt = nice_date($row_sub['dt'],"d M h:m a");
	
	$eventID = $row_sub['esID'];
	
	$fixst = $row_sub['fixstID'];
	$htid = $row_sub['ts1id'];
	$atid = $row_sub['ts2id'];
	
	
	$mID = $row_sub['mID'];
	$mname = $row_sub['mname'];
	$selID = $row_sub['selID'];
	$sel = selection_display($mID,$selID,$t1name,$t2name);

	$lID = $row_sub['lID'];
	$label = $row_sub['label'];
	
	$bt_count = bt_count($fixtureID,$session_user_id);
    $sb_count = sb_count($fixtureID,$session_user_id);
    ?>
    <div class="comment">
	    <div class="comment-content">
			<div class="comment-header">
			<?php 
					if (isset($_GET['action']) && $_GET['action']=="label" && $sbID==$_GET['id']) {
						?>
				<div>
					<form name="edit-label" method="post" action="<?php echo $URL = str_replace("&action=label", "", $url); ?>">
						<select name="labelID" required> <?php labels_dropdown($lID,$session_user_id) ?></select>
						<input type="hidden" name="sbID" value="<?php echo $sbID ?>">
						<input type="hidden" name="sID" value="02">
						<input type="submit" name="add_label" value="Add">
					</form>
				</div>
						<?php
					} else {
						echo '<a class="uname" href="' . $url . '&action=label&lID='.$lID.'&id=' . $sbID . '&sid=2">' . $label. '</a>';
					} ?>
				<span class="dt"><?= $dt ?></span>
						<ul class="list-inline pull-right">
							<li><button type="button" class="btn btn-info btn-xs" ><i class="fa fa-bullhorn fa-sm"></i></button></li>
							<li><button type="button" class="btn btn-success btn-xs anabtn" data-sbid="<?= $sbID ?>"><i class="fa fa-eye fa-sm"></i></button></li>
							<li><button type="button" class="btn btn-danger btn-xs" ><i class="fa fa-trash-o fa-sm"></i></button></li>
						</ul>
				</div>
			
			<div class="comment-body">
				<p class="l w200"><?= $mname ?></p>
				<p class="l mr"><?= $sel ?></p>
				<p class="l mr"><?= betStatus(bd_status_checker($sbType,$fixtureID,$fixst,$mID,$selID)) ?></p>
				<?php
			if (isset($_GET['action']) && $_GET['action']=="odds" && $sbID==$_GET['id']) {
				?>
				<div class="r">
				<form name="add-odds" method="post" action="<?php echo $URL = str_replace("&action=odds", "", $url); ?>">
				<input type="number" class="w70 ar" name="odds" step="any" value="<?php echo show_odds_bookie($sbType,$fixtureID,$mID,$selID) ?>">
				<select name="bookieID" required> <?php bookie_dropdown(0,$session_user_id) ?></select>
				<input type="hidden" name="eventID" value="<?php echo $eventID ?>">
				<input type="hidden" name="mID" value="<?php echo $mID ?>">
				<input type="hidden" name="selID" value="<?php echo $selID ?>">
				<input type="hidden" name="sID" value="<?php echo $sbType ?>">
				<input type="hidden" name="fixID" value="<?php echo $fixtureID ?>">
				<input type="submit" name="add_odds" value="Add">
				</form>
				</div>
				<?php
			} else {
				echo '<div class="r"><a href="' . $url . '&action=odds&id=' . $sbID . '">' . show_odds_bookie($sbType,$fixtureID,$mID,$selID). '</a></div>';
			} 
				echo '</div>';					
				echo '</div>';
				
				//include 'comments_reply.php';
				
			echo "</div>";
?>



<?php
}
echo "</div>";
?>