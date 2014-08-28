<?php $btID=mysql_real_escape_string($_GET['btid']); ?>

<h1>Bet Tracker ID <? echo $btID ?></h1>

<?php 
// procedure to draw Bet Tracker table
$sql_bt = 
"SELECT 
bt.ID AS btID,
bt.userID AS userID,
bt.date AS date,
bt.time AS time,

bt.betLinesID AS btlines,
bl.name AS blname,

bt.betStatusID AS bstID,
bst.name AS bstname,

bt.odds AS odds,
bk.name AS bkname,

bt.stake AS stake,
bt.returns AS returns,
bt.PL AS PL

FROM `betTracker` AS bt

INNER JOIN betStatus AS bst
ON bt.betStatusID=bst.ID

INNER JOIN betLines AS bl
ON bt.betLinesID=bl.ID

INNER JOIN userBookies AS ubk
ON bt.bookieID=ubk.ID

INNER JOIN bookies AS bk
ON ubk.bookieID=bk.ID

WHERE 
bt.userID = $session_user_id AND
bt.ID = $btID
" ;

$result_bt = mysql_query($sql_bt);

?>
<table class="bordered">

<tr>

<th>ID</th>

<th>Bet Type</th>

<th>Bookie</th>

<th>Total Odds</th>

<th>Total Stake</th>

<th>Total Return</th>

<th>Status</th>

<th>Total P/L</th>

<th>Created</th>

</tr>

<?php

while($row = mysql_fetch_assoc($result_bt)) {
	$bkname = $row['bkname'];

	$btID=$row['btID'];

	echo "<tr>";

	echo "<td><a href=\"index.php?page=bet-tracker&btid={$row['btID']}\">" . $row['btID'] . "</a></td>"; // betTrackerID

	// echo "<td>" . $row['userID'] . "</td>";

	echo "<td>" . $row['blname'] . "</td>"; // bet type eg. Single, double etc.

	echo "<td>" . $bkname . "</td>"; // bookie name

	echo "<td>".$row['odds']."</td>";

	echo "<td>$".$row['stake']."</td>";

	echo "<td>$".$row['returns']."</td>";

	echo "<td>".$row['bstname']."</td>";

	echo "<td>$".$row['PL']."</td>";

	echo "<td>" . $row['date'] ."<br/>" .$row['time'] . "</td>";

	echo "</tr>";

  }

echo "</table>";

?>



<table class="bordered">
<tr>

<th>ID</th>

<th>#</th>

<th>Bet Type</th>

<th>Event</th>

<th>Event Detail</th>

<th>Market</th>

<th>Bet Selection</th>

<th>Win Odds</th>

<th>Place Odds</th>

<th>Stake</th>

<th>To return</th>

<th>Status</th>

<th>Result</th>

<th>P/L</th>



</tr>

<?php 

$sql_sbType="SELECT `sbType` FROM `betDetail` WHERE `BetTrackerID` = $btID";
$result = mysql_query($sql_sbType);
$row=mysql_fetch_row($result);

$sbType = $row['0'];

switch ($sbType) {
	case '1':
		include 'bet-detail-01.php'; 
		break;

	case '2':
		include 'bet-detail-02.php'; 
		break;
	
	default:
		# code...
		break;
}

?>

</table>