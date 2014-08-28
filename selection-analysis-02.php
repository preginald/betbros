<?php 
$session_user_id = $user_data['user_id'];
$sb_where = "userID = $session_user_id AND sb.active = 1 AND sb.analyse = 1";

$sb_order = "fix.date, fix.time ASC";

$selectionCounter =0;

$result_sub = sb_table($sb_where, $sb_order);
?>
<div class="comments">
<?php
while($row_sub = mysql_fetch_array($result_sub)) {
	$sbID = $row_sub['ID'];
	$sbType = $row_sub['sID'];
	$fixtureID = $row_sub['fixID'];
	$dt = nice_date($row_sub['dt'],"d M h:m a");
	
	$eventID = $row_sub['esID'];
	
	$fixst = $row_sub['fixstID'];
	$htid = $row_sub['ts1id'];
	$atid = $row_sub['ts2id'];
	
    $t1name = $row_sub['t1name'];
	$t2name = $row_sub['t2name'];
	
	
	$mID = $row_sub['mID'];
	$mname = $row_sub['mname'];
	$selID = $row_sub['selID'];
	$sel = selection_display($mID,$selID,$t1name,$t2name);

	$lID = $row_sub['lID'];
	$label = $row_sub['label'];
	
	$bt_count = bt_count($fixtureID,$session_user_id);
    $sb_count = sb_count($fixtureID,$session_user_id);
    
    
    $selectionCounter++;?>
    <div class="comment">
    	<div class="comment-content">
			<div class="comment-header">
				<span><input type="checkbox" name="sbID02[]" id="sbID02[<?php echo $sbID?>]" value="<?php echo $sbID ?>" checked></span>
				<span class="uname"><?php echo $label ?></span>
				<span class="w300 f12">
				<?php 
				echo $t1name;
				echo ' ';
				echo matchStatus_v2($fixst,$fixtureID);
				echo ' ';
				echo $t2name; 
				?>
				</span>

				<span class="hl r">
					<ul>
						<li><a href="<?php echo $url ?>&action=analysefin&id=<?php echo $sbID ?>&sid=2">remove</a></li>
				</ul></span>
			</div>
			
			<div class="comment-body">
				<p class="l w200"><?php echo $mname ?></p>
				<p class="l mr"><?php echo $sel ?></p>
				<p class="l mr"><?php echo betStatus(bd_status_checker($sbType,$fixtureID,$fixst,$mID,$selID)) ?></p>

				<div class="r">
					<div class="r mr">
					<input type="number" class="w50 ar" step="any" name="odds[<?php echo  $sbID ?>]" id="odds[<?php echo  $sbID ?>]" min="0" style="width:4em" value="<?php echo show_odds($sbType,$fixtureID,$mID,$selID) ?>"></div>
					<input type="hidden" name="selection[]" value="<?php echo $selectionCounter ?>">
					<input type="hidden" name="label[<?php echo  $sbID ?>]" value="<?php echo $lID ?>">
				</div>
			</div>
								
		</div>
	</div>
<?php
}
?>
</div>