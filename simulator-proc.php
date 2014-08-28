<?php require_once 'core/init.php';

$option = $_GET['option'];
$newBank = $param4 = $bsID = $tBetsL = $counter=0;

function get_stake($counter,$stake=0,$stakingID,$bank=0,$bsID=0,$rPL=0,$param1=0,$param2=0,$param3=0,$param4=0) {
	switch ($stakingID) {
		case '2':
			$newBank = $_GET['bank'];
			$stake = $_GET['param1'];
			break;

		case '3':
			if ($counter == 0) {
				//$newBank = $bank;
				//$stake = $bank * $param1/100;
			} elseif($counter > 0 && $bsID == 1) {
				$newBank = $rPL;
				$stake = $rPL * $param1/100;
			} elseif($counter > 0 && $bsID == 2) {
				$newBank = $rPL;
				$stake = $rPL * $param2/100;
			} elseif($counter > 0 && $bsID == 3) {
				$newBank = $rPL;
				//$stake = $stake;
			}
			break;

		case '4':
			//echo $bank;
			$threshold = $_GET['bank'] * ($_GET['param1']/100);
			$min = $bank - $threshold;
			$max = $bank + $threshold;

			if ($rPL > $max) {
				//echo $rPL . " is greater than " . $max;
				$newBank = $bank + ($threshold);
				$stake = $newBank * ($_GET['param2']/100);
			} elseif ($rPL < $min) {
				//echo $rPL . " is less than " . $min;
				$newBank = $bank - ($threshold);
				$stake = $newBank * ($_GET['param2']/100);
			} else {
				//echo $rPL . " is greater than ". $min ." and less than " . $max;
				$newBank = $bank;
				$stake = $newBank *($_GET['param2']/100);
			}
			break;
	}
	return array('bank' => $newBank,'stake' => $stake);
}

function get_returns($stake,$odds,$bsID){
	switch ($bsID) {
		case '1':
		//echo $stake ." x ". $odds;
			return $stake * $odds;
			break;
	
		case '2':
			return 0;
			break;

		case '3':
			return $stake;
			break;

		case '4':
			return $stake;
			break;

	}
}

if ($option == "getLabels") {
	$result = labels_list($session_user_id,0,1);
	while ($row = mysql_fetch_assoc($result)) {
		?>
		<button type="button" class="btn btn-default" id="<?= $row['lID'] ?>"><?= $row['label'] ?></button>
		<?php
	}

}

if ($option == "getSelections") {
	$json = array();

	$lID = (isset($_GET['lID']) ? implode(', ', $_GET['lID']) : '');
	$stakingID = (isset($_GET['staking']) ? sanitize($_GET['staking']) : '');
	$rPL = $bank= (isset($_GET['bank'])) ? sanitize($_GET['bank']) : '';
	$stake = (isset($_GET['stake'])) ? sanitize($_GET['stake']) : '';
	$param1 = (isset($_GET['param1'])) ? sanitize($_GET['param1']) : '';
	$param2 = (isset($_GET['param2'])) ? sanitize($_GET['param2']) : '';
	$param3 = (isset($_GET['param3'])) ? sanitize($_GET['param3']) : '';
	
	//print_r_pre($_REQUEST);

	$sb_where = "userID = $session_user_id ";
	$sb_where .= " AND sb.labelID IN ( $lID ) ";
	$sb_order = "fix.date, fix.time DESC";

	$r = sb_table($sb_where, $sb_order);


	?>

	<?php
	


	while ($row = mysql_fetch_assoc($r)) {
		$t1name = $row['t1name'];
		$t2name = $row['t2name'];

		$fixtureID = $row['fixID'];
		$fixst = $row['fixstID'];
		$mID = $row['mID'];
		$selID = $row['selID'];

		$odds = get_odds(2,$fixtureID,$mID,$selID);
		$sel = selection_display($mID,$selID,$t1name,$t2name);
		//$mname = get_market($mID);
		$mabbr = get_market_abbr($mID);

		$stakeArray = get_stake($counter,$stake,$stakingID,$bank,$bsID,$rPL,$param1,$param2,$param3,$param4);	
		$stake = $stakeArray['stake'];
		$bank = $stakeArray['bank'];

		$bsID = bd_status_checker(2,$fixtureID,$fixst,$mID,$selID);
		$bstname = betStatus($bsID);

		$returns = get_returns($stake,$odds,$bsID);
		$PL = number_format($returns - $stake,2);
		$ROI = ($stake > 0) ? number_format($returns/$stake,2): '';
		$rPL += $PL;
		$line = $counter + 1;

		
		$json['html'] .= '<tr>';
		$json['html'] .= '<td class="text-right">' . $line . '</td>';
		$json['html'] .= '<td>'. $t1name ." ". matchStatus_v2($fixst,$fixtureID) ." ". $t2name . '</td>';
		$json['html'] .= '<td>'. $mabbr ." ". $sel . " @ " . $odds . '</td>';
		$json['html'] .= '<td>'. $bstname .'</td>';
		$json['html'] .= '<td class="text-right">$' . number_format($stake,2) . '</td>';
		$json['html'] .= '<td class="text-right">$' . number_format($returns,2) .'</td>';
		$json['html'] .= '<td class="text-right">$' . $PL . '</td>';
		$json['html'] .= '<td class="text-right">' . $ROI . '%</td>';
		$json['html'] .= '<td class="text-right">$' . number_format($rPL,2) .'</td>';
		$json['html'] .= '</tr>';
		 
		$counter ++;
		
		$json['tstake'] += $stake;
		$json['greturns'] += $returns;
		$json['gPL'] += $PL;

		$json['tBetsL'] += ($bsID == 2) ? 1 : "";
		$json['tBetsW'] += ($bsID == 1) ? 1 : "";
		$json['tBetsPn'] += ($bsID == 4) ? 1 : "";
		$json['tBetsPu'] += ($bsID == 3) ? 1 : "";
		$tOdds += $odds;
		
	}
	$json['tBets'] = $counter;
	$json['avgPL'] = number_format($json['gPL']/$json['tBets'],3);
	$json['avgOdds'] = number_format($tOdds/$json['tBets'],3);

	$json['sRate'] = number_format(($json['tBetsW']/$json['tBets'])*100,3);
	$json['lRate'] = number_format(($json['tBetsL']/$json['tBets'])*100,3);
	$json['puRate'] = number_format(($json['tBetsPu']/$json['tBets'])*100,3);
	$json['pnRate'] = number_format(($json['tBetsPn']/$json['tBets'])*100,3);

	$json['minOdds'] = number_format(1/($json['sRate']/100),3);


	echo json_encode($json);
}