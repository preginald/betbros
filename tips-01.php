<?php 

$sql = 
"SELECT DISTINCT
sb.racecardID AS rcID, 

sb.active,
sb.marketID AS mID, 
m.name AS mname, 
sb.selectionID AS selID, 
h.name AS hname,
hs.name AS hs

FROM `selectionBoard_01` AS sb 

INNER JOIN markets AS m ON m.ID = sb.marketID 
INNER JOIN horses AS h ON sb.horseID=h.ID 
INNER JOIN horseRaces AS hr ON hr.ID = sb.selectionID
INNER JOIN horseStatus AS hs ON hr.horseStatusID = hs.ID

WHERE sb.tip = 1 

ORDER BY sb.ID";

$result = mysql_query($sql);

while($row = mysql_fetch_array($result)) {
// for football bet selections

	echo "<tr>";

	echo '<td><input name="checkbox['. $row['ID'] .']" type="checkbox" id="checkbox['. $row['ID'] .']" value="' . $row['ID'] . '"></td>';

	echo "<td>" . count_peer_tips_01($row['rcID'],$row['mID'],$row['selID']) . "</td>";

	echo "<td>Horse Racing</td>"; // Sport name

	echo "<td>" . racecard_location($row['rcID']) . "</td>"; // Event name

	echo "<td>" . racecard_name($row['rcID']) . "</td>"; // Event detail

	echo "<td>" . $row['mname'] . "</td>"; // Market name

	echo "<td>" . $row['selID'] .". ". $row['hname'] . "</td>"; // Selection name

	echo "<td>" . $row['hs'] . "</td>"; // Horse status
  
 ?>

<td>
	<a href="index.php?page=selection-board&action=analyse&id=<?php echo $row['ID'] ?>&sid=1"><img alt="analyse" style="width: 32px; height: 32px;" title="analyse" src="images/analyse.png"></a>&nbsp;&nbsp;
	<a href="index.php?page=tips&action=tipno&rcid=<?php echo $row['rcID'] ?>&mid=<?php echo $row['mID'] ?>&selid=<?php echo $row['selID'] ?>&sid=1"><img alt="delete" style="width: 32px; height: 32px;" title="delete" src="images/delete.png"></a></td>

  </tr>

  <?php } ?>