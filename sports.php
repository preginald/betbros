<?php 

protect_page(); 

$this_week_start = date('Y-m-d', strtotime('this week')); 
$this_week_finish = date('Y-m-d', strtotime('next week'));

?>

<h1>View Sports</h1>

<?php 

// display sports table menu if 'id' exists in url

// get list of sports from sports table
if(isset($_GET['id']) && $_GET['id']=="1") {

  include 'racecards.php';
  // echo "racecards selected"; // debugging

} elseif (isset($_GET['id']) && $_GET['id']=="2") {

  // echo "football selected"; // debugging
  include 'football.php';  

} else {

  // If sports ID is not set from url then display message

  echo "<h2>Select from below</h2>";

	$sql = 
	"SELECT 
	s.ID, 
	s.name, 
	sportType.ID, 
	sportType.name, 
	contestantType.ID, 
	contestantType.name
			
	FROM sports AS s
	JOIN sportType
	ON s.sportTypeID=sportType.ID
	JOIN contestantType
	ON s.contestantTypeID=contestantType.ID

			";

	$result = mysql_query($sql);

	?>

	<table class="bordered">
	<thead>
	<tr>
	<th>ID</th>
	<th>Sport</th>
	<th>Events</th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<!-- <th></th> -->
	</tr>
	</thead>

	<?php

	while($row = mysql_fetch_array($result)) {

		$sportTypeID = $row['2'];
		$contestantTypeID = $row['4'];
		$sportID = $row['0'];

		// table for team sports and 2 contestants
		if ($sportTypeID == 2 AND $contestantTypeID == 2) { // eg football

		  	echo "<tr>";
		    echo "<td>" . $row['0'] . "</td>"; // sports ID
		    echo "<td>" . $row['1'] . "</td>"; // sport name
		    // echo "<td>" . $row['3'] . "</td>"; // team or individual
		    // echo "<td>" . $row['5'] . "</td>"; // many or head 2 head
		    echo "<td><a href=" . "index.php?page=events&view=list&sid=". $row['0'] ."&active=1" . ">current</a>  
		    		  <a href=" . "index.php?page=events&view=list&sid=". $row['0'] ."&active=2" . ">all</a></td>";  
		    echo "<td><a href=" . "index.php?page=stadium&view=list&sid=". $row['0'] . ">stadium</a></td>";  
		    echo "<td><a href=" . "index.php?page=teams&view=list&sid=". $row['0'] . ">teams</a></td>";  
		    echo "<td><a href=" . "view-teams.php?id=". $row['0'] . ">players</a></td>";  
		    echo '<td><a href="index.php?page=fixtures&view=list&sid=' . $row['0'] . '&startdate=' . $this_week_start . '&enddate=' . $this_week_finish . '&filter">fixtures</a></td>';
		    echo "<td></td>"; // blank
		    //echo "<td><a href=" . "edit-sports.php?id=". $row['0'] . ">update</a></td>";
		    echo "</tr>";
		}

		// table for individual sports and many contestants
		if ($sportTypeID == 1 AND $contestantTypeID == 3) { // horse racing

			echo "<tr>";
			echo "<td>" . $row['0'] . "</td>";
		    echo "<td>" . $row['1'] . "</td>";
		    // echo "<td>" . $row['3'] . "</td>"; // team or individual
		    // echo "<td>" . $row['5'] . "</td>"; // many or head 2 head
		    echo "<td><a href=" . "index.php?page=racecards&id=". $row['0'] . ">racecards</a></td>";  
		    echo "<td><a href=\"index.php?page=racecourses&view=list\">racecourses</a></td>";  
		    echo "<td><a href=\"index.php?page=horses&view=list\">horses</a></td>";
		    echo "<td><a href=\"index.php?page=jockeys&view=list\">jockeys</a></td>";
		    echo "<td><a href=\"index.php?page=horse-trainers&view=list\">trainers</a></td>";
		    echo "<td><a href=\"index.php?page=owners&view=list\">owners</a></td>";
		    //echo "<td><a href=" . "edit-sports.php?id=". $row['0'] . ">update</a></td>";
		    echo "</tr>";
		}

		if ($sportTypeID == 1 AND $contestantTypeID == 2) { // eg Tennis

	    	echo "<tr>";
	    	echo "<td>" . $row['0'] . "</td>";
		    echo "<td>" . $row['1'] . "</td>";
		    // echo "<td>" . $row['3'] . "</td>"; // team or individual
		    // echo "<td>" . $row['5'] . "</td>"; // many or head 2 head
		    echo "<td><a href=" . "index.php?page=events&view=list&sid=". $row['0'] ."&active=1" . ">current</a>  
		    		  <a href=" . "index.php?page=events&view=list&sid=". $row['0'] ."&active=2" . ">all</a></td>";   
		    echo "<td><a href=" . "view-events.php?id=". $row['0'] . ">venues</a></td>";  
		    echo "<td></td>"; 
		    echo "<td><a href=" . "view-teams.php?id=". $row['0'] . ">players</a></td>";
		    echo "<td><a href=" . "index.php?page=fixtures&id=". $row['0'] . ">fixtures</a></td>";
		    echo "<td></td>"; 
		   // echo "<td><a href=" . "edit-sports.php?id=". $row['0'] . ">update</a></td>";
		    echo "</tr>";
	    }

	}

	echo "</table>";

}

?>



















<!--


// Check if sports ID exists in url.

if(isset($_GET['id']) && $_GET['id']=="1") {

  include 'racecards.php';
  // echo "racecards selected"; // debugging

} elseif (isset($_GET['id']) && $_GET['id']=="2") {

  // echo "football selected"; // debugging
  include 'football.php';  

} else {

  // If sports ID is not set from url then display message

  echo "<h2>Select a sport from the menu</h2>";

}

// If sports ID exists in url then load that sport's fixtures.



// include 'selection-board-table.php';

// include 'bet-tracker-table.php';


-->