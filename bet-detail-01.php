<?php 

	if(isset($_GET['btid'])){$_GET['btid'];}  	
	$lineCounter=0;

	$sql_betDetail = 
	"SELECT 
	bd.ID AS bdID,
	bd.betTrackerID AS btID,
	bd.lineNo AS lineNo,

	bd.betLinesID AS blID,
	bl.name AS blname,

	sb.ID AS sbID,
	rc.ID AS rcID,

	sb.selectionID AS selID,
	h.name AS hname,

	m.name AS mname,

	bst.ID AS bstID,
	bst.name AS bstname,

	hstat.name AS hstat,

	bd.odds AS win_odds,
	bd.podds AS place_odds,

	bd.stake AS stake,
	bd.returns AS returns,

	bd.PL AS PL


	FROM `betDetail` AS bd

	INNER JOIN betLines AS bl
	ON bd.betLinesID=bl.ID

	INNER JOIN selectionBoard_01 AS sb
	ON bd.selectionBoardID=sb.ID

	INNER JOIN racecards AS rc
	ON sb.racecardID=rc.ID

	INNER JOIN markets AS m
	ON sb.marketID=m.ID

	INNER JOIN horses AS h
	ON sb.horseID=h.ID

	INNER JOIN betStatus AS bst
	ON bd.betStatusID=bst.ID

	INNER JOIN horseRaces AS hrace
	ON sb.selectionID = hrace.ID

	INNER JOIN horseStatus AS hstat
	ON hrace.horseStatusID = hstat.ID

	WHERE bd.betTrackerID=$btID

	ORDER BY bd.lineNo
	";

	$result_betDetail = mysql_query($sql_betDetail);

	while($row = mysql_fetch_array($result_betDetail)) {
		$lineCounter++;
		// print_r($row);

		echo "<tr>";

		echo "<td>" . $row['bdID'] . "</td>"; // Bet detail ID

		echo "<td>" . $row['lineNo'] . "</td>"; // Bet selection number 

		// echo "<td>" . $row['userID'] . "</td>";

		echo "<td>" . $row['blname'] . "</td>"; // Bet type eg. single, double, yankee...

		echo "<td>" . racecard_location($row['rcID']) . "</td>"; // Event name

		echo rc_name_td($row['rcID']); // Event detail eg. Everton v Man U  

		echo "<td>" . $row['mname'] . "</td>"; // Market eg. Win draw lose
		
		echo "<td>" . $row['selID'] .". ". $row['hname'] . "</td>"; // Bet selection eg. home, away, draw...

		echo "<td>" . $row['win_odds'] . "</td>"; // Win Odds

		echo "<td>" . $row['place_odds'] . "</td>"; // Place Odds

		//echo "<td>" . $row['bkname'] . "</td>"; // Bookie eg. bet365, sportsbet...

		echo "<td>" . $row['stake'] . "</td>"; // Stake amount

		echo "<td>$" . ($row['returns']) . "</td>"; // Expected return

		echo "<td>" . $row['bstname'] . "</td>"; // Bet Status	

		echo "<td>" . $row['hstat'] . "</td>"; // Result eg. home, draw, etc.	

		echo "<td>$" . $row['PL'] . "</td>"; // Profit / Loss... must find a way to calculate this value depending on bet status.

		echo "</tr>";
	}




?>