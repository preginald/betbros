<?php

// procedure to remove selection from selection board and move to selection board recycle bin

if(isset($_GET['sid']) && $_GET['sid']=="1") {
	$sb="selectionBoard_01";
	$where="WHERE racecardID = '$_GET[rcid]'";
} elseif (isset($_GET['sid']) && $_GET['sid']=="2") {
	$sb="selectionBoard_02";
	$where="WHERE fixtureID = '$_GET[fixid]'";
}

// procedure to disable tip
if(isset($_GET['action']) && $_GET['action']=="tipno"){
	echo "run the remove tip procedure"; // debugging
    $sql_tip_no = "UPDATE $sb SET tip=0 
    $where
    AND marketID = '$_GET[mid]'
    AND selectionID = '$_GET[selid]'
    AND userID = $session_user_id";
    $result = mysql_query($sql_tip_no);
} 

// procedure to add tip
if(isset($_GET['action']) && $_GET['action']=="tipyes"){
	echo "run the add tip procedure"; // debugging
    $sql_tip_yes = "UPDATE $sb SET tip=1 
    $where
    AND marketID = '$_GET[mid]'
    AND selectionID = '$_GET[selid]'
    AND userID = $session_user_id";
    $result = mysql_query($sql_tip_yes);
} 

// procedure to move to selection back to selection board
if (isset($_GET['action']) && $_GET['action']=="enable") {
	echo "run the enable procedure"; // debugging
	$sql_enable_selection = "UPDATE $sb SET active=1 WHERE ID = '$_GET[id]'";
	$result = mysql_query($sql_enable_selection);
}	

// procedure to add selection to the analysis table
if(isset($_GET['action']) && $_GET['action']=="analyse"){
	echo "run the analysis procedure"; // debugging
	$sql_analyse_selection = "UPDATE $sb SET analyse=1, active=1 WHERE ID = '$_GET[id]'";
	$result = mysql_query($sql_analyse_selection);
	} 

// procedure to remove selection from the analysis table
if (isset($_GET['action']) && $_GET['action']=="analysefin") {
	echo "run the remove analyse procedure"; // debugging
	$sql_remove_analyse = "UPDATE $sb SET analyse=0 WHERE ID = '$_GET[id]'";
	$result = mysql_query($sql_remove_analyse);
} 
 
 ?>

<h1>Tips</h1>

<table id="sortable" class="bordered">
<thead>
<tr>
<th></th>
<th>Peer</th>
<th>Sport</th>
<th>Event</th>
<th>Event Detail</th>
<th>Market</th>
<th>Selection</th>
<th>Status</th>
<th width="130px">Action</th>
</tr>
</thead>
<tbody>

<?php

if(isset($_GET['sb01']) && $_GET['sb01']==1){
	include 'tips-01.php';
}
if(isset($_GET['sb02']) && $_GET['sb02']==1){
	include 'tips-02.php'; 
} 
?>

</tbody>
</table>

<!--
With Selected: 
<input type="submit" value="Single" name="bet-type">
-->
