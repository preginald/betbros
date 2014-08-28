<?php 

$sb_where = "userID = $session_user_id AND sb.active = 0";

$sb_order = "fix.date, fix.time DESC";

$result = sb_table($sb_where, $sb_order);

while($row = mysql_fetch_array($result)) {

	echo "<tr>";

	echo '<td><input type="checkbox" name="betselect" value="something"></td>';

	echo '<td></td>';

	echo "<td>" . $row['ID'] . "</td>"; // ID

	echo "<td>" . $row['sname'] . "</td>"; // Sport name

	echo eventSeason($row['sID'],$row['esID']); // Event name

	echo fixture_detail($row['fixID'],1); // Event detail

	echo "<td>" . $row['mname'] . "</td>"; // Market name

	echo "<td>" . selection_display($row['mID'],$row['selID'],$row['t1name'],$row['t2name']) . "</td>"; // Selection name

	echo "<td>" . matchStatus($fixst,$fixtureID) . "</td>"; // Bet status

	echo '<td>';
	
	echo '<a href="' . $url . '&action=enable&id=' . $row['ID'] . '&sid=2"><img alt="undelete" style="width: 32px; height: 32px;" title="undelete" src="images/undelete.png"></a>';
	echo '</td>';
	echo '</tr>';

} 
?>
