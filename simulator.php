<form action="" method="post">
	<p><label for="bank" class="mr">Bank</label><input type="number" step="any" id="bank" name="bank" min="0" value="<?= $_POST['bank'] ?>" style="width:4em"></p>
	<p><label for="wstake" class="mr">Win Stake %</label><input type="number" step="any" id="wstake" name="wstake" min="0" max="100" value="<?= $_POST['wstake']?>" style="width:4em"></p>
	<p><label for="lstake" class="mr">Lose Stake %</label><input type="number" step="any" id="lstake" name="lstake" min="0" max="100" value="<?= $_POST['lstake']?>" style="width:4em"></p>
<input type="submit" value="go" name="go">
</form>


<?php
if (isset($_POST['go']) && !empty($_POST['bank']) && !empty($_POST['wstake']) && !empty($_POST['lstake'])) {
	
	$count = 0;
	$aBank = 0;
	$bank = $_POST['bank'];
	$wstake = $_POST['wstake']/100;
	$lstake = $_POST['lstake']/100;
	$stake = $bank * $wstake;

	$s = 
	"SELECT bt.ID ID, bt.date date, bt.odds odds, bt.stake stake, bt.betStatusID bsID, bs.name bs, bt.betLinesID blID, bt.PL PL FROM `betTracker` bt INNER JOIN betStatus bs ON bt.betStatusID=bs.ID
	WHERE bt.userID = $session_user_id AND bt.betStatusID != 4 ORDER BY bt.ID ASC";
	$result = mysql_query($s);?>

	<div class="w640">



				<div class="l">
					<p class="l w40 mr ac tb">
					date
					</p>

					<p class="l w20 mr ac tb">
					id
					</p>
					<p class="l w50 mr ac tb">
					odds
					</p>

					<p class="l w80 mr ac tb">
					stake
					</p>

					<p class="l w50 mr ac tb">
					w/l
					</p>

					<p class="l w70 mr ac tb">
					PL
					</p>
					<p class="l w80 mr ac tb">
					bank
					</p>

					<p class="l w50 mr ac tb">
					astake
					</p>

					<p class="l w50 mr ac tb">
					aPL
					</p>

					<p class="l w50 mr ac tb">
					abank
					</p>

				</div>







	<?php
	while ($row = mysql_fetch_assoc($result)) {
		//print_r_pre($row);

		$count ++;
		$btID = $row['ID'];
		$dt = nice_date($row['date'],"d M");
		$bsID = $row['bsID'];
		$bs = $row['bs'];
		$blID = $row['blID'];
		$odds = $row['odds'];
		$astake = $row['stake'];
		$aPL = $row['PL'];

		if ($bsID == 1) {
			$returns = $odds * $stake;
			$PL = $returns - $stake;
			$css = "win";
		}elseif ($bsID == 2) {
			$PL = 0-$stake;
			$css = "lose";
		}elseif ($bsID == 4) {
			$PL = $stake;
		}

		$aBank += $aPL;
		$bank += $PL;


		?>
		<div class="l fr p5">
			<div >
				<div class="l">
					<p class="l dt w40 mr">
						<?= $dt ?>
					</p>

					<p class="l w20 mr ar">
					<?= $btID ?>
					</p>

					<p class="l w50 mr ar">
					<?= $odds ?>
					</p>

					<p class="l w80 mr ar bbl">
					<?= number_format($stake,2) ?>
					</p>

					<p class="l w50 mr ac <?= $css ?>">
					<?= $bs ?>
					</p>

					<p class="l w70 mr ar bbl">
					<?= number_format($PL,2) ?>
					</p>

					<p class="l w80 mr ar bbl">
					<?= number_format($bank,2) ?>
					</p>

					<p class="l w50 mr ar by">
					<?= number_format($astake,2) ?>
					</p>

					<p class="l w50 mr ar by">
					<?= number_format($aPL,2) ?>
					</p>

					<p class="l w50 mr ar by">
					<?= number_format($aBank,2) ?>
					</p>

				</div>
			</div>
		</div>
	<?php

		if ($bsID == 1) {
			$stake = $wstake * $bank;
		}elseif ($bsID == 2) {
			$stake = $lstake * $bank;
		}elseif ($bsID == 4) {
			$stake = $bank;
		}
	}?>
	</div>
<?php
}
?>




<!--
[ID] => 246
[userID] => 1
[betStatusID] => 2
[date] => 2014-02-19
[time] => 01:07:54
[betLinesID] => 1
[odds] => 1.70
[bookieID] => 1
[stake] => 1.00
[returns] => 0.00
[PL] => -1.00
-->