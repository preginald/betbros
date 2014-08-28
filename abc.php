<?php
include 'core/init.php';
protect_page(); 
//include 'includes/overall/header.php';

//print_r_pre($_POST);

$message=array();
$selection=0;
$lineCounter=0;
$selectionCounter=0;

if(isset($_POST['sbID01'])) {
	if(isset($_POST['bet-type']) && !(isset($_POST['update']) || isset($_POST['add-bet'])) ) {
		$betType=htmlspecialchars($_POST['bet-type']);

		//need to check if there is each way mID(5) exists in related sbID
		$mID_array=$_POST['sbID01'];
		$IDs = join(',',$mID_array);
		$result=mysql_query(
			"SELECT marketID AS mID 
			FROM `selectionBoard_01` AS sb
			WHERE 
			marketID = 5 
			AND sb.ID in ($IDs)");
		if(mysql_num_rows($result) == 0) {
		    $eachway=FALSE;$message[] = "There aren't any each-way bets in this ACCU.";
		} else {
		    $eachway=TRUE;$message[] = "There is at least one each-way bet in this ACCU.";
		}

		$totalSelected = count($_POST['sbID01']);
		$sbID=$_POST['sbID01'];

		switch ($betType) {
			case "Single":
				if ($totalSelected > 0) {
					echo "<h1>Single bet with $totalSelected selections</h1>";
					echo '<table class="bordered">
					<tr>
					<th>ID</th>
					<th>Sport</th>
					<th>Event</th>
					<th>Event Detail</th>
					<th>Market</th>
					<th>Selection</th>
					<th>Win Odds</th>
					<th>Place Odds</th>
					<th>Bookie</th>
					<th>Stake</th>
					<th>Returns</th>
					</tr>';

					foreach ($sbID as $key => $value) {

						$sbID=$value;

						$sql_selection_analysis = 
						"SELECT
						sb.ID,
						sb.userID,

						rc.ID AS rcID,
						sb.marketID AS mID,
						m.name AS mname,

						sb.selectionID AS selID,
						h.name AS hname

						FROM `selectionBoard_01` AS sb 

						INNER JOIN racecards AS rc
						ON sb.racecardID=rc.ID

						INNER JOIN markets AS m
						ON m.ID = sb.marketID 

						INNER JOIN horses AS h
						ON sb.horseID=h.ID

						WHERE userID = $session_user_id AND sb.ID =$sbID

						ORDER BY sb.ID DESC";

						$result = mysql_query($sql_selection_analysis);
						?>
						<form action="abc.php" method="post">
						<?php

						while($row = mysql_fetch_assoc($result)) {

							//print_r_pre($row);

							echo "<tr>";

							echo "<td>" . $row['ID'] . "</td>"; // ID

							echo "<td>Horse Racing</td>"; // Sport name

							echo "<td>" . racecard_location($row['rcID']) . "</td>"; // Event name

							echo "<td>" . racecard_name($row['rcID']) . "</td>"; // Event detail

							echo "<td>" . $row['mname'] . "</td>"; // Market name

							echo "<td>" . $row['selID'] .". ". $row['hname'] . "</td>"; // Selection name

							if ($row['mID']==5) { // eachway field display
								echo '<td><input type="number" step="any" name="win_odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['win_odds'] . '"></td>'; 
								echo '<td><input type="number" step="any" name="place_odds['. $sbID .']" min="0" style="width:4em" value="' . $_POST['place_odds'] . '"></td>';
							} elseif ($row['mID']==4){ // place field display
								echo '<td>N/A</td>'; 
								echo '<td><input type="number" step="any" name="place_odds['. $sbID .']" min="0" style="width:4em" value="' . $_POST['place_odds'] . '"></td>';
							} elseif ($row['mID']==3) { // win field display
								echo '<td><input type="number" step="any" name="win_odds['. $sbID .']" min="0" style="width:4em" value="' . $_POST['win_odds'] . '"></td>'; 
								echo '<td>N/A</td>';
							}

							?>
							<td><select name="bookieID"><?php 
							$user_id = $user_data['user_id'];
							$dropDown = mysql_query(
								"SELECT userBookies.userID, bookies.ID, bookies.name, userBookies.username, userBookies.active FROM userBookies 
								INNER JOIN users ON users.user_id = userBookies.userID
								INNER JOIN bookies ON bookies.ID = userBookies.bookieID
								WHERE userBookies.userID = $user_id AND userBookies.active = 1");
							while ($record = mysql_fetch_assoc($dropDown)) {
								echo '<option value="' . $record['ID'] . '">' . $record['name'] . '</option>';
							}

							echo "</select></td>";
							?>
							<td><input type="number" step="any" name="stake[<?php echo $sbID ?>]" min="0" style="width:4em" value="<?php echo $_POST['stake'] ?>"></td>

							<td>0</td>
						
							<input type="hidden" name="sbID01[]" value="<?php echo $row['ID'] ?>">
							<input type="hidden" name="bet-type" value="<?php echo $betType ?>">

							<?php 
							echo "</tr>";
						}
					} ?>
					</table>
					<input type="submit" value="update" name="update">
					<!-- <input type="submit" value="add bet" name="add-bet">-->
					</form>
					<?php
				} else {
				echo "You must make at least 1 selection";
				}
				break;
			
			case "ACCA":

				if ($totalSelected > 1) {
					//check if duplicate rcID exists because you cannot have more than 1 similar racecard in an accu
					if (unique_rcID($IDs)) {$dup_rcID=TRUE;$message[]= "Has duplicate rcIDs";
					}

					?>
					<h1>ACCA bet with <?php echo $totalSelected ?> selections</h1>
					
					<?php foreach ($message as $key => $value) {echo $value . "<br/>";}	?>

					<form action="abc.php" method="post">
					Stake: <input type="number" step="any" name="stake[]" min="0" style="width:4em" required>	
					<select name="bookieID"> 
					<?php
					$user_id = $user_data['user_id'];
					$dropDown = mysql_query(
						"SELECT userBookies.userID, bookies.ID, bookies.name, userBookies.username, userBookies.active FROM userBookies 
						INNER JOIN users ON users.user_id = userBookies.userID
						INNER JOIN bookies ON bookies.ID = userBookies.bookieID
						WHERE userBookies.userID = $user_id AND userBookies.active = 1");
					while ($record = mysql_fetch_assoc($dropDown)) {
						echo '<option value="' . $record['ID'] . '">' . $record['name'] . '</option>';
					} echo "</select>";

					?>
					<table class="bordered">
					<tr>
					<th>ID</th>
					<th>Sport</th>
					<!--<th>Event</th>-->
					<th>Event Detail</th>
					<th>Market</th>
					<th>Selection</th>
					<th>Win Odds</th>
					<th>Place Odds</th>
					<th>Result</th>
					<th>Stake</th>
					<th>Returns</th>
					</tr>

					<?php

					foreach ($sbID as $key => $value) {
						$sbID=$value;

						$sql_selection_analysis = 
						"SELECT
						sb.ID,
						sb.userID,

						rc.ID AS rcID,
						sb.marketID AS mID,
						m.name AS mname,

						sb.selectionID AS selID,
						h.name AS hname

						FROM `selectionBoard_01` AS sb 

						INNER JOIN racecards AS rc
						ON sb.racecardID=rc.ID

						INNER JOIN markets AS m
						ON m.ID = sb.marketID 

						INNER JOIN horses AS h
						ON sb.horseID=h.ID

						WHERE userID = $session_user_id AND sb.ID =$sbID

						ORDER BY sb.ID DESC";

						$result = mysql_query($sql_selection_analysis);
						?>
						<?php

						while($row = mysql_fetch_assoc($result)) {

							//print_r_pre($row);

							echo "<tr>";

							echo "<td>" . $row['ID'] . "</td>"; // ID

							echo "<td>Horse Racing</td>"; // Sport name

							echo "<td>" . racecard_location($row['rcID']) . "</td>"; // Event name

							echo "<td>" . racecard_name($row['rcID']) . "</td>"; // Event detail

							echo "<td>" . $row['mname'] . "</td>"; // Market name

							echo "<td>" . $row['selID'] .". ". $row['hname'] . "</td>"; // Selection name

							if ($row['mID']==5) { // eachway field display
								echo '<td><input type="number" step="any" name="win_odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['win_odds'] . '"></td>'; 
								echo '<td><input type="number" step="any" name="place_odds['. $sbID .']" min="0" style="width:4em" value="' . $_POST['place_odds'] . '"></td>';
							} elseif ($row['mID']==4){ // place field display
								echo '<td>N/A</td>'; 
								echo '<td><input type="number" step="any" name="place_odds['. $sbID .']" min="0" style="width:4em" value="' . $_POST['place_odds'] . '"></td>';
							} elseif ($row['mID']==3) { // win field display
								echo '<td><input type="number" step="any" name="win_odds['. $sbID .']" min="0" style="width:4em" value="' . $_POST['win_odds'] . '"></td>'; 
								echo '<td>N/A</td>';
							}

							?>
							<td><select required name="status[<?php echo $row['ID'] ?>]"><?php horse_race_status(15) ?></select></td>  
	    					<input type="hidden" name="id[]" value="<?php echo $row3['hrid'] ?>">
							<td><input type="number" step="any" name="stake[]" min="0" style="width:4em" value="" readonly	></td>

							<td>0</td>
						
							<input type="hidden" name="sbID01[]" value="<?php echo $row['ID'] ?>">
							<input type="hidden" name="bet-type" value="<?php echo $betType ?>">
							<?php
							if($eachway)  {echo '<input type="hidden" name="eachway" value=1>' ;}  
							if($dup_rcID) {echo '<input type="hidden" name="dup_rcID" value=1>' ;} 
							?>
							<!-- <input type="hidden" name="bet-type" value="Single"> -->
							<?php 
							echo "</tr>";
						}
					} ?>
					</table>

					<input type="submit" value="update" name="update">
					<!-- <input type="submit" value="add bet" name="add-bet">-->
					</form>
					<?php
				} else {
				echo "You must make at least 2 selections";
				}
				break;
			
			case "Trixie":
				echo "Trixie selected";

				if ($totalSelected > 2){
					echo "Trixie bet with $totalSelected selections";
				} else {
					echo "You must make at least 3 selections";
				}

				break;
			
			case "Patent":
				echo "Patent selected";

				if ($totalSelected > 3){
					echo "Trixie bet with $totalSelected selections";
				} else {
					echo "You must make at least 3 selections";
				}
				break;
			
			case "Yankee":
				echo "Yankee selected";

				if ($totalSelected > 3){
					echo "Trixie bet with $totalSelected selections";
				} else {
					echo "You must make at least 3 selections";
				}
				break;

				case "Lucky 15":
				echo "Lucky 15 selected";

				if ($totalSelected > 3){
					echo "Trixie bet with $totalSelected selections";

				} else {

					echo "You must make at least 3 selections";
				}

				break;
			
			default:
				# code...
				break;
		}
	}
}

if(isset($_POST['sbID02'])) {
	// echo "Football bet type";
	if(isset($_POST['bet-type']) && !(isset($_POST['update']) || isset($_POST['add-bet'])) ) {
		$betType=htmlspecialchars($_POST['bet-type']);

		$totalSelected = count($_POST['sbID02']);
		$sbID=$_POST['sbID02'];
		$sbIDx=$sbID[$lineCounter];

		$IDs = join(',',$sbID);


		switch ($_POST['bet-type']) {
			case "Single":
				if ($totalSelected > 0){
					if (!empty($_POST['label'])) {
						$lID = $_POST['label'][$sbIDx];
						$stakingID = get_stakingID($lID);
					}?>
					<p>Single bet with <?=$totalSelected?> selections</p>
					<form action="abc.php" method="post">
					<!-- dynamic form goes here -->
					<?php include 'stakingforms.php'; ?>
					<table class="bordered">
					<tr>
					<th>#</th>
					<th>ID</th>
					<th>Sport</th>
					<th>Event Detail</th>
					<th>Market</th>
					<th>Selection</th>
					<th>Odds</th>
					<th>Bookie</th>
					<th>Stake</th>
					<th>Returns</th>
					</tr>
					<?php
					foreach ($sbID as $key => $value) {
						$selectionCounter++;
						$sbID=$value;

						$sql_selection_analysis = 
						"SELECT 
						sb.ID, 
						sb.userID, 
						sb.fixtureID AS fixID,
						sb.marketID AS mID,

						s.id AS sID,
						s.name AS sname,

						e.name AS ename,
						es.ID AS esID,

						t1.name AS t1name, 
						t2.name AS t2name,

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

						INNER JOIN teamSeason AS ts1
						ON fix.homeTeamID=ts1.ID

						INNER JOIN teams AS t1
						ON ts1.teamID=t1.ID

						INNER JOIN teamSeason AS ts2
						ON fix.awayTeamID=ts2.ID

						INNER JOIN teams AS t2
						ON ts2.teamID=t2.ID

						WHERE userID = $session_user_id AND sb.ID =$sbID

						ORDER BY sb.ID DESC";

						$result = mysql_query($sql_selection_analysis);

						while($row = mysql_fetch_assoc($result)) {
							?>
							<tr>
							<td><?= $selectionCounter ?></td>
							<td><?= $row['ID'] ?></td>
							<td><?= $row['sname'] ?></td>
							<?= fixture_detail($row['fixID'],1) ?>
							<td><?= $row['mname'] ?>"</td>
							<td><?=selection_display($row['mID'],$row['selID'],$row['t1name'],$row['t2name']) ?></td>
							<td><input class="w50 ar" type="number" step="any" name="odds[<?= $sbID ?>]" min="0" value="<?= $_POST['odds'][$sbID] ?>"></td>
							<td><select name="bookieID" id="bookieID" required ><?php bookie_dropdown(0,$session_user_id) ?></select></td>
							<td><input class="w80 ar" type="number" step="any" name="stake[<?php echo $sbID ?>]" min="0" value="<?= $stake ?>" readonly></td>

							<td><input class="w70 ar" type="number" name="returns" min="0" value="<?php echo $_POST['odds'][$sbID] * $_POST['stake'][$sbID] ?>" readonly></td>
							
							<input type="hidden" name="sbID02[]" value="<?php echo $row['ID'] ?>">
							<input type="hidden" name="bet-type" value="<?php echo $betType ?>">
							
							<?php 
							echo "</tr>";
						}
					} ?>

					</table>
					<input type="submit" value="update" name="update">
					</form>
					<?php
				} else {
					echo "You must make at least 1 selection";
				}

					break;

			case "ACCA":

				if ($totalSelected > 1){
					//check if duplicate rcID exists because you cannot have more than 1 similar racecard in an accu
					if (unique_fixID($IDs)) {$dup_fixID=TRUE;$message[]= "Has duplicate fixIDs";
					}

					?>
					<h1>ACCA bet with <?php echo $totalSelected ?> selections</h1>

					<?php foreach ($message as $key => $value) {echo $value . "<br/>";}	?>

					<form action="abc.php" method="post">
					Stake: <input type="number" step="any" name="stake[]" min="0" style="width:4em" required>	
					<td><select name="bookieID" id="bookieID" required ><?php bookie_dropdown() ?></select></td>
					<table class="bordered">
					<tr>
					<th>ID</th>
					<th>Sport</th>
					<th>Event Detail</th>
					<th>Market</th>
					<th>Selection</th>
					<th>Win Odds</th>
					<th>Place Odds</th>
					<th>Result</th>
					<th>Stake</th>
					<th>Returns</th>
					</tr>

					<?php

					foreach ($sbID as $key => $value) {
						$sbID=$value;

						$sql_selection_analysis = 
						"SELECT 
						sb.ID, 
						sb.userID, 
						sb.fixtureID AS fixID,
						sb.marketID AS mID,

						s.id AS sID,
						s.name AS sname,

						e.name AS ename,
						es.ID AS esID,

						t1.name AS t1name, 
						t2.name AS t2name,

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

						INNER JOIN teamSeason AS ts1
						ON fix.homeTeamID=ts1.ID

						INNER JOIN teams AS t1
						ON ts1.teamID=t1.ID

						INNER JOIN teamSeason AS ts2
						ON fix.awayTeamID=ts2.ID

						INNER JOIN teams AS t2
						ON ts2.teamID=t2.ID

						WHERE userID = $session_user_id AND sb.ID =$sbID

						ORDER BY sb.ID DESC";

						$result = mysql_query($sql_selection_analysis);
						?>
						<?php

						while($row = mysql_fetch_assoc($result)) {

							//print_r_pre($row);

							echo "<tr>";

							echo "<td>" . $row['ID'] . "</td>"; // ID

							echo "<td>Football</td>"; // Sport name

							//echo eventSeason($row['sID'],$row['esID']); // Event name

							echo fixture_detail($row['fixID'],1); // Event detail

							echo "<td>" . $row['mname'] . "</td>"; // Market name

							echo "<td>" . selection_display($row['mID'],$row['selID'],$row['t1name'],$row['t2name']) . "</td>"; // Selection name

							echo '<td><input type="number" step="any" name="odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['odds'] . '"></td>'; 

							?>
							<td></td>  
	    					<input type="hidden" name="id[]" value="<?php echo $row3['hrid'] ?>">
							<td></td>

							<td>0</td>
						
							<input type="hidden" name="sbID02[]" value="<?php echo $row['ID'] ?>">
							<input type="hidden" name="bet-type" value="<?php echo $betType ?>">
							<?php 
							if($dup_fixID) {echo '<input type="hidden" name="dup_fixID" value=1>' ;} 
							?>
							<!-- <input type="hidden" name="bet-type" value="Single"> -->
							<?php 
							echo "</tr>";
						}
					} ?>
					</table>

					<input type="submit" value="update" name="update">
					<!-- <input type="submit" value="add bet" name="add-bet">-->
					</form>
					<?php

				} else {


					echo "You must make at least 2 selections";
				}

				break;

			case "Trixie":
				echo "Trixie selected";

				if ($totalSelected > 2){
					echo "Trixie bet with $totalSelected selections";

				} else {
					echo "You must make at least 3 selections";
				}

				break;

			case "Patent":
				echo "Patent selected";

				if ($totalSelected > 3){
				
					echo "Trixie bet with $totalSelected selections";

				} else {
					echo "You must make at least 3 selections";
				}

				break;

			case "Yankee":
				echo "Yankee selected";

				if ($totalSelected > 3){
					echo "Trixie bet with $totalSelected selections";
				} else {
					echo "You must make at least 3 selections";
				}

				break;
				
			case "Lucky 15":
				echo "Lucky 15 selected";

				if ($totalSelected > 3){
					echo "Trixie bet with $totalSelected selections";

				} else {
					echo "You must make at least 3 selections";
				}

				break;
			
			default:
				# code...
				break;
		}
	}
}

if(isset($_POST['update']) && $_POST['bet-type']=="Single") {
	$betType=htmlspecialchars($_POST['bet-type']);
	?>



	<?php
	if(isset($_POST['sbID01'])) {
		$sbID=$_POST['sbID01'];
		// Horse Racing bet type
		'<table class="bordered">
		<tr>
		<th><input type="checkbox" name="checkbox id="checkbox" value="0"></th>
		<th>#</th>
		<th>ID</th>
		<th>Sport</th>
		<th>Event</th>
		<th>Event Detail</th>
		<th>Market</th>
		<th>Selection</th>
		<th>Win Odds</th>
		<th>Place Odds</th>
		<th>Bookie</th>
		<th>Stake</th>
		<th>Returns</th>
		</tr>';
		echo '<form action="abc.php" method="post">';
		foreach ($sbID as $key => $value) {
			$sbID=$value;

			echo $sql_selection_analysis = 
			"SELECT
			sb.ID,
			sb.userID,
			sb.marketID AS mID,
			sb.selectionID AS selID,

			rc.ID AS rcID,

			sb.active,

			m.name AS mname,

			h.name AS hname

			FROM `selectionBoard_01` AS sb 

			INNER JOIN racecards AS rc
			ON sb.racecardID=rc.ID

			INNER JOIN markets AS m
			ON m.ID = sb.marketID 

			INNER JOIN horses AS h
			ON sb.horseID=h.ID

			WHERE userID = $session_user_id AND sb.ID =$sbID

			ORDER BY sb.ID DESC";

			$result = mysql_query($sql_selection_analysis);

			while($row = mysql_fetch_assoc($result)) {

				echo "<tr>";

				echo '<td><input type="checkbox" name="sbID01[]" id="sbID01['. $row['ID'] .']" value="' . $row['ID'] . '"></td>';

				echo "<input type=\"hidden\" name=\"selection[]\" value=\"$selectionCounter\">";
				
				echo '<td></td>';

				echo "<td>" . $row['ID'] . "</td>"; // ID

				echo "<td>Horse Racing</td>"; // Sport name

				echo "<td>" . racecard_location($row['rcID']) . "</td>"; // Event name

				echo "<td>" . racecard_name($row['rcID']) . "</td>"; // Event detail

				echo "<td>" . $row['mname'] . "</td>"; // Market name

				echo "<td>" . $row['selID'] .". ". $row['hname'] . "</td>"; // Selection name
							
				if ($row['mID']==5) { // each way field display
					echo '<td><input type="number" step="any" name="win_odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['win_odds'][$sbID] . '"></td>'; 
					echo '<td><input type="number" step="any" name="place_odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['place_odds'][$sbID] . '"></td>';
				} elseif ($row['mID']==4) { // place field display
					echo '<td>N/A</td>';
					echo '<td><input type="number" step="any" name="place_odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['place_odds'][$sbID] . '"></td>';	
				} elseif ($row['mID']==3) { // win field display
					echo '<td><input type="number" step="any" name="win_odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['win_odds'][$sbID] . '"></td>'; 
					echo '<td>N/A</td>';
				}
				?>

				 <td><select name="bookieID"><?php 
				$user_id = $user_data['user_id'];
				$dropDown = mysql_query("SELECT userBookies.userID, bookies.ID, bookies.name, userBookies.username, userBookies.active FROM userBookies 
										INNER JOIN users ON users.user_id = userBookies.userID
										INNER JOIN bookies ON bookies.ID = userBookies.bookieID
										WHERE userBookies.userID = $user_id AND userBookies.active = 1");

				while ($record = mysql_fetch_assoc($dropDown)) {
				//print_r($record);
					echo '<option value="' . $record['ID'] . '">' . $record['name'] . '</option>';
				}

				echo "</select></td>";
				?>
				<td><input type="number" step="any" name="stake[<?php echo $sbID ?>]" min="0" style="width:4em" value="<?php echo $_POST['stake'][$sbID] ?>"></td>

				<td><?php echo $returns = each_way($_POST['win_odds'][$sbID],$_POST['place_odds'][$sbID],$_POST['stake'][$sbID]) ?></td>
				
				<input type="hidden" name="sbID01[]" value="<?php echo $row['ID'] ?>">
				<input type="hidden" name="rcID[<?php echo $sbID ?>]" value="<?php echo $row['rcID'] ?>">
				<input type="hidden" name="selID[<?php echo $sbID ?>]" value="<?php echo $row['selID'] ?>">
				<input type="hidden" name="mID[<?php echo $sbID ?>]" value="<?php echo $row['mID'] ?>">
				<input type="hidden" name="returns[<?php echo $sbID ?>]" value="<?php echo $returns ?>">
				<input type="hidden" name="bet-type" value="<?php echo $betType ?>">
				<?php 
				echo "</tr>";
			}
		}

		?>
		
		</table>
		<input type="submit" value="update" name="update">
		<input type="submit" value="add bet" name="add-bet">
		</form>

		<?php
	}

	if(isset($_POST['sbID02'])) {
		$tstake = $total = 0;
		$sbID=$_POST['sbID02'];
		$fixed = $_POST['fixed'];
		$percent = $_POST['percent'];
		$bookieID = $_POST['bookieID'];
		$bank = $_POST['bank'];
		$label = $_POST['label'];
		if ($fixed) {
			$fstake = $fixed;
		}elseif (!empty($percent) && !empty($bank)) {
			$fstake = $bank * ($percent/100);
		}else{
			unset($fstake);
		}
		// echo "Football bet type";
		?>
		<form action="abc.php" method="post">

		<label for="label">Label</label>
		<select name="label" id="label" required ><?php labels_dropdown($lID,$session_user_id) ?></select>
		<table class="bordered">
		<tr>
		<th><input type="checkbox" name="checkbox" id="checkbox" value="0"></th>
		<th>#</th>
		<th>ID</th>
		<th>Sport</th>
		<th>Event Detail</th>
		<th>Market</th>
		<th>Selection</th>
		<th>Odds</th>
		<th>Bookie</th>
		<th>Stake</th>
		<th>Result</th>
		<th>Returns</th>
		</tr>
		<?php
		foreach ($sbID as $key => $value) {
			$sbID=$value;
			$sql_selection_analysis = 
						"SELECT 
						sb.ID, 
						sb.userID, 
						sb.fixtureID AS fixID,
						sb.marketID AS mID,

						s.id AS sID,
						s.name AS sname,

						e.name AS ename,
						es.ID AS esID,

						t1.name AS t1name, 
						t2.name AS t2name,

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

						INNER JOIN teamSeason AS ts1
						ON fix.homeTeamID=ts1.ID

						INNER JOIN teams AS t1
						ON ts1.teamID=t1.ID

						INNER JOIN teamSeason AS ts2
						ON fix.awayTeamID=ts2.ID

						INNER JOIN teams AS t2
						ON ts2.teamID=t2.ID

						WHERE userID = $session_user_id AND sb.ID =$sbID

						ORDER BY sb.ID DESC";

			$result = mysql_query($sql_selection_analysis);

			while($row = mysql_fetch_assoc($result)) {
				$stake = (isset($fstake)) ? $fstake : $_POST['stake'][$row['ID']];
				$odds = $_POST['odds'][$row['ID']];
				$status = $_POST['status'][$row['ID']];
				echo "<tr>";

				echo '<td><input type="checkbox" name="sbID[]" id="sID['. $row['ID'] .']" value="' . $row['ID'] . '"></td>';
				echo '<td>'. $selectionCounter . '</td>';

				echo "<td>" . $row['ID'] . "</td>"; // ID

				echo "<td>" . $row['sname'] . "</td>"; // Sport name

				//echo eventSeason($row['sID'],$row['esID']); // Event name

				echo fixture_detail($row['fixID'],1); // Event detail

				echo "<td>" . $row['mname'] . "</td>"; // Market name

				echo "<td>" . selection_display($row['mID'],$row['selID'],$row['t1name'],$row['t2name']) . "</td>"; // Selection name

				echo '<td><input type="number" step="any" name="odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['odds'][$sbID]  . '"></td>'; 

				?>

				<td><select name="bookieID" id="bookieID" required ><?php bookie_dropdown($bookieID,$session_user_id) ?></select></td>
				<td><input type="number" step="any" name="stake[<?=$sbID?>]" min="0" style="width:4em" value="<?=$stake?>"></td>

				<td><select required name="status[<?php echo $row['ID'] ?>]"><?php bet_status($_POST['status'][$row['ID']]) ?></td>

				<td>
					<?php 
					if($status == 1){
						echo $return = $stake * $odds;
					} elseif ($_POST['status'][$row['ID']] == 2) {
						echo $return = 0;
					} elseif ($_POST['status'][$row['ID']] == 3) {
						echo $return = $stake;
					}
					$tstake += $stake;
					$total += $return;
					?>
				</td>
				
				<input type="hidden" name="sbID02[]" value="<?php echo $row['ID'] ?>">
				<input type="hidden" name="fixID[<?=$sbID?>]" value="<?=$row['fixID']?>">
				<input type="hidden" name="selID[<?=$sbID?>]" value="<?=$row['selID']?>">
				<input type="hidden" name="mID[<?=$sbID?>]" value="<?=$row['mID']?>">
				<input type="hidden" name="returns[<?=$sbID?>]" value="<?=$return?>">
				<input type="hidden" name="bet-type" value="<?=$betType?>">
				<?php 
				echo "</tr>";
			}
		}

		?>
		</table>
		<input type="submit" value="update" name="update">
		<input type="submit" value="add bet" name="add-bet">
		</form>
		<b>Total Stake</b>: <?php echo number_format($tstake, 2, '.', ''); ?> 
		<b>Total Returns</b>:  <?php echo number_format($total, 2, '.', ''); ?>   
		<b>Total PL</b>: <?php echo number_format($total - $tstake, 2, '.', ''); ?>  
		<b>Avg Odds</b>: <?php echo number_format($total/$tstake, 2, '.', ''); ?>
		<b>Profit %</b>: <?php echo number_format((($total - $tstake)/$tstake)*100, 2, '.', ''); ?>
		<?php
	}
}

if(isset($_POST['update']) && $_POST['bet-type']=="ACCA") {

	$betType=htmlspecialchars($_POST['bet-type']);
	$lineCounter=0;

	if(isset($_POST['sbID01'])) {
		$sbID=$_POST['sbID01'];
		// Horse Racing bet type
		?>

		<form action="abc.php" method="post">
			Stake: <input type="number" step="any" name="stake[0]" min="0" style="width:4em" value="<?php echo $_POST['stake'][0] ?>">	
			<select name="bookieID"> 
			<?php
			$user_id = $user_data['user_id'];
			$dropDown = mysql_query(
				"SELECT userBookies.userID, bookies.ID, bookies.name, userBookies.username, userBookies.active FROM userBookies 
				INNER JOIN users ON users.user_id = userBookies.userID
				INNER JOIN bookies ON bookies.ID = userBookies.bookieID
				WHERE userBookies.userID = $user_id AND userBookies.active = 1"
				);
			while ($record = mysql_fetch_assoc($dropDown)) {
				echo '<option value="' . $record['ID'] . '">' . $record['name'] . '</option>';
			} echo "</select>";
		?>
		<table class="bordered">
		<tr>

		<th><input type="checkbox" name="checkbox" value="0"></th>

		<th>#</th>

		<th>ID</th>

		<th>Sport</th>

		<th>Event</th>

		<th>Event Detail</th>

		<th>Market</th>

		<th>Selection</th>

		<th>Win Odds</th>
		
		<th>Place Odds</th>

		<th>Result</th>

		<th>Stake</th>

		<th>Returns</th>

		</tr>
		<?php
		foreach ($sbID as $key => $value) {
			$sbID=$value;

			$sql_selection_analysis = 
			"SELECT
			sb.ID,
			sb.userID,
			sb.marketID AS mID,
			sb.selectionID AS selID,

			rc.ID AS rcID,

			sb.active,

			m.name AS mname,

			h.name AS hname

			FROM `selectionBoard_01` AS sb 

			INNER JOIN racecards AS rc
			ON sb.racecardID=rc.ID

			INNER JOIN markets AS m
			ON m.ID = sb.marketID 

			INNER JOIN horses AS h
			ON sb.horseID=h.ID

			WHERE userID = $session_user_id AND sb.ID =$sbID

			ORDER BY sb.ID DESC";

			$result = mysql_query($sql_selection_analysis);

			while($row = mysql_fetch_assoc($result)) {
				$lineCounter++;
				//print_r_pre($row);

				echo "<tr>";

				echo '<td><input type="checkbox" name="sbID01[]" id="sbID01['. $row['ID'] .']" value="' . $row['ID'] . '"></td>';

				echo '<input type="hidden" name="selection['. $row['ID'] .']" value="'.$lineCounter.'">';
				
				echo "<td>". $lineCounter."</td>";

				echo "<td>" . $row['ID'] . "</td>"; // ID

				echo "<td>Horse Racing</td>"; // Sport name

				echo "<td>" . racecard_location($row['rcID']) . "</td>"; // Event name

				echo "<td>" . racecard_name($row['rcID']) . "</td>"; // Event detail

				echo "<td>" . $row['mname'] . "</td>"; // Market name

				echo "<td>" . $row['selID'] .". ". $row['hname'] . "</td>"; // Selection name
							
				if ($row['mID']==5) { // each way field display
					echo '<td><input type="number" step="any" name="win_odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['win_odds'][$sbID] . '"></td>'; 
					echo '<td><input type="number" step="any" name="place_odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['place_odds'][$sbID] . '"></td>';
				} elseif ($row['mID']==4) { // place field display
					echo '<td>N/A</td>';
					echo '<td><input type="number" step="any" name="place_odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['place_odds'][$sbID] . '"></td>';	
				} elseif ($row['mID']==3) { // win field display
					echo '<td><input type="number" step="any" name="win_odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['win_odds'][$sbID] . '"></td>'; 
					echo '<td>N/A</td>';
				}
				?>
				<td><select required name="status[<?php echo $row['ID'] ?>]"><?php horse_race_status($_POST['status'][$row['ID']]) ?></select></td>  

				<td><input type="number" step="any" name="stake[]" min="0" style="width:4em" value="<?php echo accu_ew_stake($lineCounter,$return_carryover,$row['mID'],$_POST['stake'][0]); ?>" readonly></td>

				<td>
					<?php
					$hsID=$_POST['status'][$sbID];
					if ($hsID==14) { // horse did not win or place
						$return_carryover=0;
					}elseif ($hsID==15) { // horse won
						if ($row['mID']==3) { // win market
							if(isset($return_carryover)){
								echo $return_carryover=$return_carryover*$_POST['win_odds'][$row['ID']];
							}else{
								echo $return_carryover=$_POST['stake'][$lineCounter]*$_POST['win_odds'][$row['ID']];
							}
						} elseif ($row['mID']==4) { // place market
							if(isset($return_carryover)){
								echo $return_carryover=$return_carryover*$_POST['place_odds'][$row['ID']];
							}else{
								echo $return_carryover=$_POST['stake'][$lineCounter]*$_POST['place_odds'][$row['ID']];
							}
						} elseif ($row['mID']==5) { // each way market
							if(isset($return_carryover)){
								echo $return_carryover=(($return_carryover/2)*$_POST['win_odds'][$row['ID']])+(($return_carryover/2)*$_POST['place_odds'][$row['ID']]);
							}else{
								echo $return_carryover=(($_POST['stake'][$lineCounter]/2)*$_POST['win_odds'][$row['ID']])+(($_POST['stake'][$lineCounter]/2)*$_POST['place_odds'][$row['ID']]);
							}
						}
					}elseif ($hsID==16) { // horse placed and did not win
						if ($row['mID']==4) { // place market
							if(isset($return_carryover)){
								echo $return_carryover=$return_carryover*$_POST['place_odds'][$row['ID']];
							}else{
								echo $return_carryover=$_POST['stake'][$lineCounter]*$_POST['place_odds'][$row['ID']];
							}
						} elseif ($row['mID']==5) { // each way market
							if(isset($return_carryover)){
								echo $return_carryover=(($return_carryover/2)*$_POST['win_odds'][$row['ID']])+(($return_carryover/2)*$_POST['place_odds'][$row['ID']]);
							}else{
								echo $return_carryover=($_POST['stake'][$lineCounter]*0)+($_POST['stake'][$lineCounter]*$_POST['place_odds'][$row['ID']]);
							}
						} elseif ($row['mID']==3) { // win market
							echo $return_carryover=0;
						}
					}
					
					?></td>
				
				<input type="hidden" name="sbID01[]" value="<?php echo $row['ID'] ?>">
				<input type="hidden" name="rcID[<?php echo $sbID ?>]" value="<?php echo $row['rcID'] ?>">
				<input type="hidden" name="selID[<?php echo $sbID ?>]" value="<?php echo $row['selID'] ?>">
				<input type="hidden" name="mID[<?php echo $sbID ?>]" value="<?php echo $row['mID'] ?>">

				<?php 
				echo "</tr>";
			}
		}

		?>
		</table>
		<input type="hidden" name="bet-type" value="<?php echo $betType ?>">
		<?php if($_POST['eachway'])  {echo '<input type="hidden" name="eachway" value=1>' ;} ?>
		<input type="submit" value="update" name="update">
		<?php 
		if ($_POST['dup_rcID']) {echo "Cannot add bet due to invalid accu selections";}
		else{echo '<input type="submit" value="add bet" name="add-bet">';}
		?>
		</form>
		<?php
	}

	if(isset($_POST['sbID02'])) {
		$sbID=$_POST['sbID02'];
		// Football bet type
		?>

		<form action="abc.php" method="post">
			Stake: <input type="number" step="any" name="stake[0]" min="0" style="width:4em" value="<?php echo $_POST['stake'][0] ?>">	
			<select name="bookieID"> 
			<?php
			$user_id = $user_data['user_id'];
			$dropDown = mysql_query(
				"SELECT userBookies.userID, bookies.ID, bookies.name, userBookies.username, userBookies.active FROM userBookies 
				INNER JOIN users ON users.user_id = userBookies.userID
				INNER JOIN bookies ON bookies.ID = userBookies.bookieID
				WHERE userBookies.userID = $user_id AND userBookies.active = 1"
				);
			while ($record = mysql_fetch_assoc($dropDown)) {
				echo '<option value="' . $record['ID'] . '">' . $record['name'] . '</option>';
			} echo "</select>";
		?>
		<table class="bordered">
		<tr>

		<th></th>

		<th>#</th>

		<th>ID</th>

		<th>Sport</th>

		<th>Event Detail</th>

		<th>Market</th>

		<th>Selection</th>

		<th>Win Odds</th>
		
		<th>Place Odds</th>

		<th>Result</th>

		<th>Stake</th>

		<th>Returns</th>

		</tr>
		<?php
		foreach ($sbID as $key => $value) {
			$sbID=$value;

			$sql_selection_analysis = 
						"SELECT 
						sb.ID, 
						sb.userID, 
						sb.fixtureID AS fixID,
						sb.marketID AS mID,

						s.id AS sID,
						s.name AS sname,

						e.name AS ename,
						es.ID AS esID,

						t1.name AS t1name, 
						t2.name AS t2name,

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

						INNER JOIN teamSeason AS ts1
						ON fix.homeTeamID=ts1.ID

						INNER JOIN teams AS t1
						ON ts1.teamID=t1.ID

						INNER JOIN teamSeason AS ts2
						ON fix.awayTeamID=ts2.ID

						INNER JOIN teams AS t2
						ON ts2.teamID=t2.ID

						WHERE userID = $session_user_id AND sb.ID =$sbID

						ORDER BY sb.ID DESC";

			$result = mysql_query($sql_selection_analysis);

			while($row = mysql_fetch_assoc($result)) {
				$lineCounter++;
				//print_r_pre($row);

				echo "<tr>";

				echo '<td><input type="checkbox" name="sbID01[]" id="sbID01['. $row['ID'] .']" value="' . $row['ID'] . '"></td>';
				
				echo "<td>". $lineCounter."</td>";

				echo "<td>" . $row['ID'] . "</td>"; // ID

				echo "<td>Football</td>"; // Sport name

				//echo eventSeason($row['sID'],$row['esID']); // Event name

				echo fixture_detail($row['fixID'],1); // Event detail

				echo "<td>" . $row['mname'] . "</td>"; // Market name

				echo "<td>" . selection_display($row['mID'],$row['selID'],$row['t1name'],$row['t2name']) . "</td>"; // Selection name
							
				echo '<td><input type="number" step="any" name="odds[' . $sbID . ']" min="0" style="width:4em" value="' . $_POST['odds'][$sbID] . '"></td>'; 

				?>
				<td></td>  

				<td><select required name="status[<?php echo $row['ID'] ?>]"><?php bet_status($_POST['status'][$row['ID']]) ?></td>

				<td><?php echo $stake = ($lineCounter == 1) ? $_POST['stake'][0] : $return; ?>
				</td>

				<td><?php 
					if($_POST['status'][$row['ID']] == 1){
						echo $return = $stake * $_POST['odds'][$row['ID']];
					} elseif ($_POST['status'][$row['ID']] == 2) {
						echo $return = 0;
					} elseif ($_POST['status'][$row['ID']] == 3) {
						echo $return = $stake;
					}
				
				?>
				</td>
				<input type="hidden" name="selection[<?php $row['ID'] ?>]" value="<?php $lineCounter ?>">
				<input type="hidden" name="return[<?php echo $row['ID'] ?>]" value="<?php echo $return ?>">
				<input type="hidden" name="stake[<?php echo $row['ID'] ?>]" value="<?php echo $stake ?>">
				<input type="hidden" name="sbID02[]" value="<?php echo $row['ID'] ?>">
				<input type="hidden" name="fixID[<?php echo $sbID ?>]" value="<?php echo $row['fixID'] ?>">
				<input type="hidden" name="selID[<?php echo $sbID ?>]" value="<?php echo $row['selID'] ?>">
				<input type="hidden" name="mID[<?php echo $sbID ?>]" value="<?php echo $row['mID'] ?>">

				<?php 
				echo "</tr>";
			}
		}
		?>
		</table>
		<input type="hidden" name="bet-type" value="<?php echo $betType ?>">
		<input type="submit" value="update" name="update">
		<?php 
		if ($_POST['dup_fixID']) {echo "Cannot add bet due to invalid accu selections";}
		else{echo '<input type="submit" value="add bet" name="add-bet">';}
		?>
		</form>
		<?php
		$stake = $_POST['stake'][0];
		$profit = $return - $stake;
		$profitMargin = ($profit/$stake) * 100;
		echo '<br/>';
		echo '<div><b>Total Return:</b> ' . $return . ' <b>Total Profit:</b> ' . $profit . ' <b>Profit Margin:</b> ' . $profitMargin .'%</div>';
	}
}

if(isset($_POST['add-bet']) && $_POST['bet-type']=="Single") {

	if(isset($_POST['sbID01'])) {
		// Horse Racing bet type
		$sbID=$_POST['sbID01'];
		$bookieID = sanitize($_POST['bookieID']);

		foreach ($sbID as $key => $value) {
			$sbID=$value;

			$rcID = sanitize($_POST['rcID'][$sbID]);
			$selID = sanitize($_POST['selID'][$sbID]);
			$odds = sanitize($_POST['win_odds'][$sbID]) ;
			$podds = sanitize($_POST['place_odds'][$sbID]);
			$stake = sanitize($_POST['stake'][$sbID]); 
			$mID = sanitize($_POST['mID'][$sbID]);

			// if bet market is eachway bet then multiply stake by 2 lines
			if (!empty($odds) && !empty($podds)) {
				$stake = $stake * 2;
			}
			$returns = sanitize($_POST['returns'][$sbID]);
			$PL=0-$stake;


			$createDate = date("Y-m-d");
			$createTime = date("H:i:s");
			$userID = $session_user_id;

			// return selected horse's horseStatusID
			$hsID = mysql_result(mysql_query("SELECT hr.horseStatusID AS hsID 
			FROM `selectionBoard_01` AS sb
			JOIN horseRaces AS hr ON sb.selectionID=hr.ID
			WHERE sb.ID = $sbID AND sb.racecardID = $rcID AND sb.selectionID = $selID"), 0);

			$sql_insert_bt = 
			"INSERT INTO `betTracker`(`userID`, `betStatusID`, `date`, `time`, `betLinesID`, `odds`, `bookieID`, `stake`, `returns`,`PL`) 
			VALUES ('$userID',4,'$createDate','$createTime',1,'$odds',$bookieID,'$stake','$returns','$PL')";

		    $result=mysql_query($sql_insert_bt);

		    // if successfully added

		    if($result){

		    	$btID = mysql_insert_id();
		    	echo "successfully added btID $btID<br/>";
		    	$sql_insert_bd =
		    	"INSERT INTO `betDetail`
				(`selectionBoardID`,`sbType`,`betLinesID`, `betStatusID`, `betTrackerID`, `odds`, `podds`, `bookieID`, `stake`, `returns`,`PL`) 
				VALUES 
				('$sbID',1,1,4,'$btID','$odds','$podds','$bookieID','$stake','$returns','$PL')";

				$result=mysql_query($sql_insert_bd);

				if($result){
					// echo "successfully added to bd";
					echo horse_bet_update($selID,$hsID);

				} else {
					echo "ERROR";
				}

		    } else {

		      echo "ERROR";
		    }
		}
	}

	if(isset($_POST['sbID02'])) {
		// echo "Football bet type";
		$sbID = $_POST['sbID02'];
		$counter = 0;
		foreach ($sbID as $key => $value) {
			$sbID=$value;

			$fixID = $fixtureID = sanitize($_POST['fixID'][$counter]);
			$selID = sanitize($_POST['selID'][$counter]);
			$odds = sanitize($_POST['odds'][$counter]) ;
			$stake = sanitize($_POST['stake'][$counter]); 
			$mID = sanitize($_POST['mID'][$counter]);
			$bookieID = sanitize($_POST['bookieID'][$counter]);
			$label = sanitize($_POST['labelID'][$counter]);

			$returns = 0;
			$PL=0;

			$createDate = date("Y-m-d");
			$createTime = date("H:i:s");
			$userID = $session_user_id;

			$sql_insert_bt = 
			"INSERT INTO `betTracker`(`userID`, `betStatusID`, `date`, `time`,`labelID`, `betLinesID`, `odds`, `bookieID`, `stake`, `returns`,`PL`) 
			VALUES ('$userID',4,'$createDate','$createTime','$label',1,'$odds',$bookieID,'$stake','$returns','$PL')";

		    $result=mysql_query($sql_insert_bt);

		    // if successfully added

		    if($result){

		    	$btID = mysql_insert_id();
		    	echo "successfully added btID $btID";
		    	$sql_insert_bd =
		    	"INSERT INTO `betDetail`
				(`selectionBoardID`,`sbType`,`betLinesID`, `betStatusID`, `betTrackerID`, `odds`, `bookieID`, `stake`, `returns`,`PL`) 
				VALUES 
				('$sbID',2,1,4,'$btID','$odds','$bookieID','$stake','$returns','$PL')";

				$result=mysql_query($sql_insert_bd);

				if($result){
					$bdID = mysql_insert_id();
					//echo "successfully added to bd";
					echo football_betDetail_update($fixtureID);
					odds_insert($odds,2,$fixID,$mID,$selID,$bookieID,$userID);
				} else {
					echo "ERROR";
				}

		    } else {
		      echo "ERROR";
		    }
		    $counter ++;
		}
	}
}

if(isset($_POST['add-bet']) && $_POST['bet-type']=="ACCA") {
	unset($btID);
	unset($return_carryover);
	$lineNo = 0;
	$bookieID = mysql_real_escape_string($_POST['bookieID']);

	$stake = mysql_real_escape_string($_POST['stake'][0]);
	if($_POST['eachway'])  {$stake=$stake*2 ;}

	if(isset($_POST['sbID01'])) {
		// Horse Racing bet type

		$sbID=$_POST['sbID01'];
		$rcIDs=$_POST['rcID'];
		$selIDs=$_POST['selID'];
		$PL=0-$stake;

		//this procedure will return an array of horse status IDs for accu selections.
		foreach ($sbID as $key => $value) {
			$hsIDs[]= mysql_result(mysql_query(
				"SELECT horseStatusID AS hsID 
				FROM horseRaces
				WHERE 
				`racecardID` = $rcIDs[$value] AND 
				`ID`= $selIDs[$value]"), 0);
		}

		//this will find out if any hsID of 1 exists in array, ie. horse "To Run".
		//because there is no point updating betTracker if a horse has not finished.
		$bet_update = (in_array(1, $hsIDs)) ? 1:0;

		foreach ($sbID as $key => $value) {
			
			//echo "$key=".$key;
			if ($key==0) {
				$bd_stake=$stake;
				$f_stake=$stake; //this "first stake" value will be passed to the horse_accu_bet_update($selID,$hsID,$key,$f_stake) function
			}elseif ($key>0) {
				$bd_stake=0;
				$f_stake="x";
			}
			
			//note: $value = current $sbID in array

			//$lineNo = mysql_real_escape_string($_POST['selection'][$value]); 
			
			$lineNo ++;
			$rcID = mysql_real_escape_string($_POST['rcID'][$value]);
			$selID = mysql_real_escape_string($_POST['selID'][$value]);
			$odds = mysql_real_escape_string($_POST['win_odds'][$value]) ;
			$podds = mysql_real_escape_string($_POST['place_odds'][$value]);
			$mID = mysql_real_escape_string($_POST['mID'][$value]);

			$createDate = date("Y-m-d");
			$createTime = date("H:i:s");
			$userID = $session_user_id;

			// return selected horse's horseStatusID
			$hsID = mysql_result(mysql_query(
				"SELECT horseStatusID AS hsID 
				FROM horseRaces
				WHERE 
				racecardID = $rcID AND 
				ID = $selID"), 0);

			if(isset($btID)) {
				//checking if btID exists to prevent duplicate betTracker creation.				
			} else {
				$sql_insert_bt = 
				"INSERT INTO `betTracker`(`userID`, `betStatusID`, `date`, `time`, `betLinesID`, `odds`, `bookieID`, `stake`, `returns`, `PL`) 
				VALUES ('$userID',4,'$createDate','$createTime',2,'0','$bookieID','$stake','0','$PL')";

		    	$result=mysql_query($sql_insert_bt);
		    	$btID = mysql_insert_id();
			}

		    // if successfully added

		    if($result){
		    	if ($key==0) {echo "successfully added betTrackerID: $btID <br/>";} //only displays this message once
		    	$sql_insert_bd =
		    	"INSERT INTO `betDetail`
				(`lineNo`,`selectionBoardID`,`sbType`,`betLinesID`, `betStatusID`, `betTrackerID`, `odds`, `podds`, `stake`, `returns`) 
				VALUES 
		
				('$lineNo','$value',1,2,4,'$btID','$odds','$podds','$bd_stake','$returns')";

				$result=mysql_query($sql_insert_bd);
				$bdID=mysql_insert_id();

				if($result){
					echo "inserted bdID: $bdID<br/>";
					if ($bet_update==1) {
						if ($key==0) {echo "skip betDetail update process because a horse yet to run <br/>";} //only displays this message once
					}else{
						//echo "bdID: $bdID, key: $key, firstStake: $f_stake<br/>";
						//$return_carryover = horse_accu_bet_update($bdID,$key,$f_stake,$return_carryover);
					}

				} else {
					echo "ERROR";
				}

		    } else {

		      echo "ERROR";
		    }
		}
	}

	if(isset($_POST['sbID02'])) {
		// Football bet type

		$sbID=$_POST['sbID02'];
		$fixIDs=$_POST['fixID'];
		$selIDs=$_POST['selID'];
		$PL=0;
		$label = $_POST['label'];
		//this procedure will return an array of fixture status IDs for accu selections.
		foreach ($sbID as $key => $value) {
			echo $fsIDs[]= mysql_result(mysql_query(
				"SELECT fixtureStatusID AS fsID 
				FROM fixtures
				WHERE 
				`ID` = $fixIDs[$value]"), 0);
		}

		//this will find out if any fsID of 1 exists in array, ie. horse "To Run".
		//because there is no point updating betTracker if a horse has not finished.
		$bet_update = (!in_array(4, $fsIDs)) ? 1:0;

		foreach ($sbID as $key => $value) {
			
			//echo "$key=".$key;
			if ($key==0) {
				$bd_stake=$stake;
				$f_stake=$stake; //this "first stake" value will be passed to the horse_accu_bet_update($selID,$hsID,$key,$f_stake) function
			}elseif ($key>0) {
				$bd_stake=0;
				$f_stake="x";
			}

			//note: $value = current $sbID in array

			//$lineNo = mysql_real_escape_string($_POST['selection'][$value]); 
			
			$lineNo ++;
			$fixID = mysql_real_escape_string($_POST['fixID'][$value]);
			$selID = mysql_real_escape_string($_POST['selID'][$value]);
			$odds = mysql_real_escape_string($_POST['odds'][$value]) ;
			$mID = mysql_real_escape_string($_POST['mID'][$value]);

			$createDate = date("Y-m-d");
			$createTime = date("H:i:s");
			$userID = $session_user_id;

			if(isset($btID)) {
				//checking if btID exists to prevent duplicate betTracker creation.				
			} else {
				$sql_insert_bt = 
				"INSERT INTO `betTracker`(`userID`, `betStatusID`, `date`, `time`,`labelID`, `betLinesID`, `odds`, `bookieID`, `stake`, `returns`, `PL`) 
				VALUES ('$userID',4,'$createDate','$createTime','$label',2,'0','$bookieID','$stake','0','$PL')";

		    	$result=mysql_query($sql_insert_bt);
		    	$btID = mysql_insert_id();
			}

		    // if successfully added

		    if($result){
		    	if ($key==0) {echo "successfully added betTrackerID: $btID <br/>";} //only displays this message once
		    	$sql_insert_bd =
		    	"INSERT INTO `betDetail`
				(`lineNo`,`selectionBoardID`,`sbType`,`betLinesID`, `betStatusID`, `betTrackerID`, `odds`, `stake`, `returns`) 
				VALUES 
		
				('$lineNo','$value',2,2,4,'$btID','$odds','$bd_stake','$returns')";

				$result=mysql_query($sql_insert_bd);
				$bdID=mysql_insert_id();

				if($result){
					echo "inserted bdID: $bdID<br/>";
					if ($bet_update==1) {
						if ($key==0) {echo "skip betDetail update process because match is not final<br/>";} //only displays this message once
					// add odds data to odds table if a matching one doesn't exist.
					odds_insert($odds,2,$fixID,$mID,$selID,$bookieID,$userID);
					}else{

					}

				} else {
					echo "ERROR";
				}

		    } else {

		      echo "ERROR";
		    }
		}
	}

	//echo accu_bet_tracker_update($btID);
}

//bt_update_s1();
//bt_update_s2();

//include 'bet-tracker-table.php';
// include 'includes/overall/footer.php'; ?>