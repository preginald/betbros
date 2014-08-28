<?php 

switch ($stakingID) {
	case '1':
		?>
		<label for="fixed">Fixed</label>
		<input type="number" step="any" name="fixed" id="fixed" min="0" style="width:4em" value=""></td>
		Or
		<label for="percent">%</label>
		<input type="number" step="any" name="percent" id="percent" min="0" max="100" style="width:4em" value=""></td>
		<label for="bank">of Bank</label>
		<input type="number" step="any" name="bank" id="bank" min="0" style="width:4em" value=""></td>
		<label for="label">Label</label>
		<td><select name="label" id="label" required ><?php labels_dropdown($lID,$session_user_id) ?></select></td>
		<?php
		break;

	case '2':
		$usID = get_usID($lID);
		$usArray = get_staking_param($usID);
		$stake = $usArray['param1'];
		$PL_label = get_PL_label($lID);
		$bank_label = get_bank_label_value($lID);
		$bank = number_format($bank_label + $PL_label,2);
		?>
		<label for="fixed">Fixed</label>
		<input class="ar w80" type="number" step="any" name="fixed" id="fixed" min="0" value="<?= $stake?>" readonly>
		<label for="bank" >Bank balance</label>
		<input class="ar w100" type="number" step="any" name="bank" id="bank" min="0" value="<?= $bank?>" readonly>
		<?php
		break;

	case '3':
		$bsID = get_betStatusID_last($session_user_id);
		$usID = get_usID($lID);
		$usArray = get_staking_param($usID);
		$winStake = $usArray['param1'];
		$loseStake = $usArray['param2'];
		$rounding = $usArray['param3'];

		function rounding($rounding,$stake){
			switch ($rounding) {
				case '1': // rounding up
					return round($stake, 0, PHP_ROUND_HALF_UP);
					break;

				case '2': // rounding down
					return round($stake, 0, PHP_ROUND_HALF_DOWN);
					break;
				
				default:
					return $stake;
					break;
			}			
		}

		$PL_label = get_PL_label($lID);
		$bank_label = get_bank_label_value($lID);
		$bank = number_format($bank_label + $PL_label,2);
		// if bsID is 1 then get win % else if lose then get lose %
		if ($bsID==1) {
			$stake = rounding($rounding,number_format(($winStake/100) * $bank,2));
			?><p>Your last bet <span class="tb">won</span> so we'll be applying <span class="tb"><?= $winStake ?> %</span> of your <span class="tb"><?= $bank ?></span> bank balance</p>
		<?php 
		}elseif ($bsID==2) {
			$stake = rounding($rounding,number_format(($loseStake/100) * $bank,2));
			?><p>Your last bet <span class="tb">lost</span> so we'll be applying <span class="tb"><?= $loseStake ?> %</span> of your <span class="tb"><?= $bank ?></span> bank balance</p>
		<?php 
		}elseif ($bsID==4) {
			$stake = 0;
			?><p>Your last bet <span class="tb">is pending</span> so you cannot place another bet until the previous bet has resulted.</p>
		<?php 
		}
		?>
		<label for="winStake" >Win %</label>
		<input class="ar" type="number" step="any" name="winStake" id="winStake" min="0" max="100" style="width:4em" value="<?= $winStake?>" readonly>
		<label for="loseStake" >Lose %</label>
		<input class="ar" type="number" step="any" name="loseStake" id="loseStake" min="0" max="100" style="width:4em" value="<?= $loseStake?>" readonly>
		<label for="bank" >of Bank</label>
		<input class="ar w100" type="number" step="any" name="bank" id="bank" min="0" value="<?= $bank?>" readonly>
		<?php

		break;
	
	default:
		# code...
		break;
}

?>
