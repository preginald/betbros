<?php

$bt_url = curPageURLshort();
$bt_where = '';
$total =0;
$win =0;
$lose =0;
$push =0;
$pending = 0;
// url string functions]

//$btsfarray = array( "All", "Win", "Lose", "Void", "Pending", "Resulted");
$btsfarray = array( "All", "Win", "Lose", "Void", "Pending", "Resulted");
$last = end($btsfarray);


echo '<div>';
foreach ($btsfarray as $key => $value) {

	if (!isset($_GET['btsf'])) {

		$URL = $bt_url . "&btsf=$key";
		echo '<a href="' . $URL  . '">' . $value .'</a>';
		if ($value == $last) { } else {echo " | ";} 

	 } elseif (isset($_GET['btsf']) && $_GET['btsf'] != $key) {
		$btsf=$_GET['btsf'];
		$URL = str_replace("&btsf=$btsf","&btsf=$key",$bt_url);
		echo '<a href="' . $URL  . '">' . $value .'</a>';
		if ($value == $last) { } else {echo " | ";} 
	} else {
		echo "<b>$value</b>";
		if ($value == $last) { } else {echo " | ";} 
	}
}
echo '</div>';


// all=0, win=1, lose=2, void=3, pending=4
if (isset($_GET['btsf']) && !empty($_GET['btsf'])) {

	$btsf=$_GET['btsf'];
	$bt_where = "bt.userID = $session_user_id";
	switch ($btsf) {
		case '1': // win
			$bt_where .= " AND bd.betStatusID='$btsf'";
			break;

		case '2': // lost
			$bt_where .= " AND bd.betStatusID='$btsf'";
			break;

		case '3': // void
			$bt_where .= " AND bd.betStatusID='$btsf'";
			break;

		case '4': // pending
			$bt_where .= " AND bs.betStatusID='$btsf'";
			break;

		case '5': // not pending
			$bt_where .= " AND bd.betStatusID !='4'";
			break;

		case '6':
			$bt_where .= " AND bd.betStatusID='$btsf'";
			break;

		default:
			$bt_where .= " AND bd.betStatusID IN(1,2,3,4)";
			break;
	}
} else {$bt_where = "bt.userID = $session_user_id AND bd.betStatusID IN(1,2,3,4)";}






$return = bd_sb_list($bt_where);

	echo '<table class="bordered">';

	echo '<tr>';
	
	echo '<th>ID</th>';
	echo '<th>label</th>';
	//echo '<td>'.$row['sbType'].'</td>';
	echo '<th></th>';
	echo '<th>W odds</th>';
	echo '<th>P odds</th>';
	echo '<th>Event Detail</th>';
	echo '<th>Market</th>';
	echo '<th>Selection</th>';	
	
	echo '</tr>';

while($row = mysql_fetch_array($return)){

	$sbID = $row['sbID'];
	$sbType = $row['sbType'];
	$betStatusID = $row['betStatusID'];
	
	//echo "$sbID, $sbType, $betStatusID <br/>";
	
	$row = (bd_sb_list_details($sbID, $sbType));
	//print_r_pre($row);
	
	echo '<tr>';
	
	echo '<td>'.$row['sbID'].'</td>';
	echo '<td>'.$row['label'].'</td>';
	//echo '<td>'.$row['sbType'].'</td>';
	echo '<td>'.$row['bsname'].'</td>';
	echo '<td>'.$row['odds'].'</td>';
	echo '<td>'.$row['podds'].'</td>';
	echo '<td>'. eventType($sbType,$row['eventID']) .'</td>';
	echo '<td>'.$row['mabbr'].'</td>';
	echo '<td>'.$row['selname'].'</td>';	
	
	echo '</tr>';
	
	$odds += $row['odds'];
	$odds += $row['podds'];
	
	$total += ($row['bsID']) ? 1:0;
	$win += ($row['bsID'] == 1) ? 1:0;
	$lose += ($row['bsID'] == 2) ? 1:0;
	$push += ($row['bsID'] == 3) ? 1:0;
	$pending += ($row['bsID'] == 4) ? 1:0;
	
}

echo '</table>';

$winratio = number_format($win/$total,2)*100;
$loseratio = number_format($lose/$total,2)*100;
$avg_odds = number_format($odds/$total,2);
$avg_win = number_format($avg_odds/$win,2);
$be_odds = number_format(1/($winratio/100),2);

echo "<br/>";
echo "Win: $win ($winratio%) BE Odds: $be_odds<br/>";
echo "Lose: $lose ($loseratio%) <br/>";
echo "Push: $push<br/>";
echo "Pending: $pending<br/>";
echo "Total: $total <br/>";
echo "Average Odds: $avg_odds <br/>";


?>