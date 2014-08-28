<?php 

$sql_selection_analysis = 

"SELECT 
sb.ID, 
sb.userID, 
sb.racecardID AS rcID, 

sb.active, 
sb.marketID AS mID,
m.name AS mname, 
sb.selectionID AS selID, 
h.name AS hname,
hs.name AS hs

FROM `selectionBoard_01` AS sb 

INNER JOIN markets AS m ON m.ID = sb.marketID 

INNER JOIN horseRaces AS hr ON hr.ID = sb.selectionID
INNER JOIN horses AS h ON hr.horseID=h.ID 
INNER JOIN horseStatus AS hs ON hr.horseStatusID = hs.ID

WHERE userID = $session_user_id AND sb.active = 1 AND sb.analyse = 1

ORDER BY sb.ID DESC";

$result = mysql_query($sql_selection_analysis);
$count = mysql_num_rows($result);

?>

<?php
while($row = mysql_fetch_array($result)) {
	//print_r($row);                                                                      

	$selectionCounter++;

	echo "<tr>";

	echo '<td><input type="checkbox" name="sbID01[]" id="sbID01['. $row['ID'] .']" value="' . $row['ID'] . '"></td>';

	echo '<td>'. $selectionCounter . '</td>';

	echo '<td></td>';

	echo "<td>" . $row['ID'] . "</td>"; // ID

	echo "<td>Horse Racing</td>"; // Sport name

	echo "<td>" . racecard_location($row['rcID']) . "</td>"; // Event name

	echo "<td>" . racecard_name($row['rcID']) . "</td>"; // Event detail

	echo "<td>" . $row['mname'] . "</td>"; // Market name

	echo "<td>" . $row['selID'] .". ". $row['hname'] . "</td>"; // Selection name

	//echo "<input type=\"hidden\" name=\"selection[]\" value=\"$selectionCounter\">";
	//echo "<input type=\"hidden\" name=\"mID[]\" value=\"{$row['mID']}\">";
	//echo "<input type=\"hidden\" name=\"rcID[{$row['ID']}]\" value=\"{$row['rcID']}\">";

	echo "<td>" . $row['hs'] . "</td>"; // Horse status


	echo '<td>';
	echo '<a href="' . $url . '&action=tipyes&fixid=' . $fixtureID . '&mid=' . $mID . '&selid=' . $selID . '&sid=2"><img alt="update" style="width: 32px; height: 32px;" title="update" src="images/update.png"></a>';
	echo '<a href="' . $url . '&action=analysefin&id=' . $row['ID'] . '&sid=1"><img alt="analyse" style="width: 32px; height: 32px;" title="analyse" src="images/analyse.png"></a>';
	echo '<a href="' . $url . '&action=disable&id=' . $row['ID'] . '&sid=1"><img alt="delete" style="width: 32px; height: 32px;" title="delete" src="images/delete.png"></a>';
	echo '</td>';
	echo '</tr>';

} 
?>