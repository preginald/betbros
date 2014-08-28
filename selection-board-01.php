<?php 

if (isset($_GET['action'])) {
	$url = array_shift(explode('&action', $url));
} else {
	$url = curPageURLshort();
}

$sql = 
"SELECT 
sb.ID, 
sb.userID, 
sb.racecardID AS rcID, 

sb.active, 
m.name AS mname, 
sb.selectionID AS selID, 
h.name AS hname,
hs.name AS hs

FROM `selectionBoard_01` AS sb 

INNER JOIN markets AS m ON m.ID = sb.marketID 
INNER JOIN horses AS h ON sb.horseID=h.ID 
INNER JOIN horseRaces AS hr ON hr.ID = sb.selectionID
INNER JOIN horseStatus AS hs ON hr.horseStatusID = hs.ID

WHERE sb.userID = 1 AND sb.analyse != 1 

ORDER BY sb.ID";

$result = mysql_query($sql);

while($row = mysql_fetch_array($result)) {
// for football bet selections

	echo "<tr>";

	echo '<td><input name="checkbox['. $row['ID'] .']" type="checkbox" id="checkbox['. $row['ID'] .']" value="' . $row['ID'] . '"></td>';

	echo '<td></td>';

	echo "<td>" . $row['ID'] . "</td>"; // ID

	echo "<td>Horse Racing</td>"; // Sport name

	echo "<td>" . racecard_location($row['rcID']) . "</td>"; // Event name

	echo "<td>" . racecard_name($row['rcID']) . "</td>"; // Event detail

	echo "<td>" . $row['mname'] . "</td>"; // Market name

	echo "<td>" . $row['selID'] .". ". $row['hname'] . "</td>"; // Selection name

	echo "<td>" . $row['hs'] . "</td>"; // Horse status

	echo '<td>';
	echo '<a href="' . $url . '&action=tipyes&id=' . $row['ID'] . '&mid=' . $mID . '&selid=' . $selID . '&sid=2"><img alt="update" style="width: 32px; height: 32px;" title="update" src="images/update.png"></a>';
	echo '<a href="' . $url . '&action=analyse&id=' . $row['ID'] . '&sid=1"><img alt="analyse" style="width: 32px; height: 32px;" title="analyse" src="images/analyse.png"></a>';
	echo '<a href="' . $url . '&action=disable&id=' . $row['ID'] . '&sid=1"><img alt="delete" style="width: 32px; height: 32px;" title="delete" src="images/delete.png"></a>';
	echo '</td>';
	echo '</tr>';

}

?>