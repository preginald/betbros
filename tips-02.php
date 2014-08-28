<?php 

$sql = 
	"SELECT DISTINCT
	sb.ID, 
	sb.userID, 
	sb.fixtureID AS fixID,
	sb.marketID AS mID,

	s.id AS sID,
	s.name AS sname,

	e.name AS ename,

	es.ID AS esID,

	fix.fixtureStatusID AS fixstID,

	t1.name AS t1name, 
	t1.ID AS htid,
	ts1.ID AS ts1id,

	t2.name AS t2name,
	t2.ID AS atid,
	ts2.ID AS ts2id,

	m.name AS mname,

	sel.ID AS selID,
	sel.name AS selname,

	sb.active

	FROM selectionBoard_02 AS sb

	INNER JOIN markets AS m
	ON m.ID = sb.marketID 

	INNER JOIN selection AS sel
	ON sel.ID = sb.selectionID 

	INNER JOIN fixtures AS fix
	ON sb.fixtureID=fix.ID

	INNER JOIN eventSeason AS es
	ON fix.eventSeasonID=es.ID

	INNER JOIN events AS e
	ON es.eventsID=e.ID

	INNER JOIN sports AS s
	ON e.sportID=s.ID

	INNER JOIN teamSeason ts1 
	ON fix.homeTeamID=ts1.ID

	INNER JOIN teams t1 
	ON ts1.teamID=t1.ID

	INNER JOIN teamSeason ts2 
	ON fix.awayTeamID=ts2.ID

	INNER JOIN teams t2 
	ON ts2.teamID=t2.ID

	WHERE sb.tip = 1
	AND fix.fixtureStatusID !=4

	ORDER BY sb.ID";

$result = mysql_query($sql);
//$count = mysql_num_array($result);

while($row = mysql_fetch_array($result)) {
// for football bet selections

	$fixtureID = $row['fixID'];
	$fixst = $row['fixstID'];
	$htid = $row['ts1id'];
	$atid = $row['ts2id'];
	$mID = $row['mID'];
	$selID = $row['selID'];

	$htscore = total_home_goals($fixtureID,$htid,$atid);
	$atscore = total_away_goals($fixtureID,$htid,$atid);

	$matchStatus = ($fixst == 1) ? "Pending" : fixture_ftr($htscore,$atscore)."<br/>" . $htscore ." - ". $atscore;

	echo "<tr>";

	echo '<td><input name="checkbox['. $row['ID'] .']" type="checkbox" id="checkbox['. $row['ID'] .']" value="' . $row['ID'] . '"></td>';

	echo "<td>" . count_peer_tips_02($fixtureID,$row['mID'],$row['selID']) . "</td>";

	echo "<td>" . $row['sname'] . "</td>"; // Sport name

	echo "<td>" . fixture_list_heading($row['sID'],$row['esID']) . "</td>"; // Event name

	echo "<td><a href=\"index.php?page=fixtures&view=detail&sid=2&esid={$row['esID']}&fixid={$row['fixID']}&htid={$htid}&atid={$atid}&addshot=0&addmatchtime=0&addlineup=0\">" . fixture_detail_heading($row['fixID'],1) . "</a></td>"; // Event detail

	echo "<td>" . $row['mname'] . "</td>"; // Market name

	echo "<td>" . selection_display($row['mID'],$row['selID'],$row['t1name'],$row['t2name']) . "</td>"; // Selection name

	echo "<td>" . $matchStatus . "</td>"; // Bet status
  
 ?>

<td>
	<a href="index.php?page=tips&action=analyse&id=<?php echo $row['ID'] ?>&sid=2"><img alt="analyse" style="width: 32px; height: 32px;" title="analyse" src="images/analyse.png"></a>&nbsp;&nbsp;
	<a href="index.php?page=tips&action=tipno&fixid=<?php echo $fixtureID ?>&mid=<?php echo $mID ?>&selid=<?php echo $selID ?>&sid=2"><img alt="delete" style="width: 32px; height: 32px;" title="delete" src="images/delete.png"></a>

  </tr>

  <?php } ?>