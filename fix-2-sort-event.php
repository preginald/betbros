<?php	
	$sql1 = 
    "SELECT DISTINCT s.ID as sID, fix.eventSeasonID AS esID
    FROM fixtures AS fix 
    INNER JOIN eventSeason AS es ON es.ID=fix.eventSeasonID 
    INNER JOIN events AS e ON e.ID=es.eventsID 
    INNER JOIN sports AS s ON s.ID=e.sportID
    $where ";
	?>
	<div class="fc">
	<?php 
	$result1 = mysql_query($sql1);
	while($row1 = mysql_fetch_assoc($result1)){
		$sub_sID = $row1['sID'];
		$sub_esID = $row1['esID'];

		$subHeading = eventSeasonHeading($sub_sID,$sub_esID);

		$sql = 
		"SELECT 
		fix.ID,
		u.username AS user,
		e.ID as eid,
		s.ID as sid,
		fix.date, 
		fix.time,

		fix.eventSeasonID AS esID,

		e.name as ename,
		spn.name as spnname,
		es.startDate AS sdate,
		es.endDate AS edate,

		t1.ID AS t1id,
		ts1.ID AS ts1id,
		t1.name AS t1name,

		t2.ID AS t2id,
		ts2.ID AS ts2id,
		t2.name AS t2name,

		c.name AS cname,
		c.alpha_2 AS ccode,

		fix.fixtureStatusID AS fixstID,
		fixstat.name AS fixstat

		FROM fixtures AS fix

		INNER JOIN users AS u
		ON fix.createUserID=u.user_id

		INNER JOIN eventSeason AS es
		ON es.ID=fix.eventSeasonID

		INNER JOIN events AS e
		ON e.ID=es.eventsID

		INNER JOIN brands AS spn
		ON spn.ID=es.sponsorID

		INNER JOIN sports AS s
		ON s.ID=e.sportID

		INNER JOIN teamSeason AS ts1
		ON fix.homeTeamID=ts1.ID

		INNER JOIN teams AS t1
		ON ts1.teamID=t1.ID

		INNER JOIN teamSeason AS ts2
		ON fix.awayTeamID=ts2.ID

		INNER JOIN teams AS t2
		ON ts2.teamID=t2.ID

		INNER JOIN countries AS c
		ON c.id=e.countryID

		INNER JOIN fixtureStatus AS fixstat
		ON fix.fixtureStatusID=fixstat.ID

		$where AND fix.eventSeasonID = $sub_esID

		ORDER BY fix.date, fix.time
		";

		$result = mysql_query($sql);?>
		
		<div class="fb">
		
		<?php
		if (empty($_GET['esid'])) {echo '<div class="fh">' .$subHeading. '</div>'; }

		while($row = mysql_fetch_assoc($result)) {

			$mysqldate = $row['date'];
			$audate = date('D d-M', strtotime($mysqldate));
			$mysqltime = $row['time'];
			$time = date('g:i a', strtotime($mysqltime));
			$htid = $row['ts1id'];
			$atid = $row['ts2id'];
			$sbType = $row['sid'];
      $fixtureID = $row['ID'];
			$fixst = $row['fixstID'];
			$uname = $row['user'];
			
			$eventSeasonID = $eventID = $esID = $row['esID'];

			$bt_count = bt_count($fixtureID,$session_user_id);
			$sb_count = sb_count($fixtureID,$session_user_id);

			$t1name = $row['t1name'];
			$t2name = $row['t2name'];
		  $cms = cm_count($fixtureID,0);

			if (!isset($_GET['mk'])) {
				$URL = $url . "&mk=$fixtureID";
				$mklink = '<a href="' . $URL  . '">markets</a>';
			}elseif(isset($_GET['mk']) && ($_GET['mk']) == $fixtureID){
				$URL = str_replace("&mk=$mk","",$url);
				$mklink = '<a href="' . $URL  . '">markets</a>';
			}else{
				$mk = $_GET['mk'];
				$URL = str_replace("&mk=$mk","&mk=$fixtureID",$url);
				$mklink = '<a href="' . $URL  . '">markets</a>';
			}
		  
			if (!isset($_GET['cm'])) {
				$URL = $url . "&cm=$fixtureID";
				$cmlink = '<a href="' . $URL  . '">';
			}elseif(isset($_GET['cm']) && ($_GET['cm']) == $fixtureID){
				$cm = $_GET['cm'];
				$URL = str_replace("&cm=$cm","",$url);
				$cmlink = '<a href="' . $URL  . '">';
			}else{
				$cm = $_GET['cm'];
				$URL = str_replace("&cm=$cm","&cm=$fixtureID",$url);
				$cmlink = '<a href="' . $URL  . '">';
			}
		  


			?>

			
			<div class="fr">

			<div class="l w620 p5">
      <p class="l dt w130">
      <?php echo $audate . " " . $time ?>
      </p>

			<p class="l w300 mr f12">
			<a href="index.php?page=teams&view=detail&sid=<?php echo $sportsID;?>&esid=<?php echo $eventSeasonID ?>&tsid=<?php echo $htid ?>"><?php echo $t1name ?></a>
			
			<a href="index.php?page=fixtures&view=detail&sid=<?php echo $sportsID;?>&esid=<?php echo $eventSeasonID ?>&fixid=<?php echo $row['ID'] . fixture_home_away_url($row['ID']) . score_url() ?> ">

			<?php echo matchStatus_v2($fixst,$fixtureID) ?></a>
			
			<a href="index.php?page=teams&view=detail&sid=<?php echo $sportsID;?>&esid=<?php echo $eventSeasonID ?>&tsid=<?php echo $atid ?>"><?php echo $t2name ?></a>  
      </p>

      <div class="commentbtn">
        <?php
        if($cms == 0){
          echo $cmlink . 'No comments</a>';
        } elseif($cms == 1){
          echo $cmlink . $cms . ' comment</a>';
        } else {
        echo $cmlink . $cms . ' comments</a>';
        }
        ?>
      </div>

      <div class="fxmkbtn">
      <?php echo $mklink ?>
      </div>

			</div>
			
			<?php
			if(isset($_GET['mk']) && $_GET['mk'] == $fixtureID){
				include 'fixture_markets.php';
			}
			if(isset($_GET['cm']) && $_GET['cm'] == $fixtureID){
				include 'comments.php';
			}?>		
				<div class="frf">
				
				<p class="id">
				<?php echo $fixtureID ?>
				</p>

				<p class="uname">
				<?php echo $uname ?>
				</p>
				
				<p>
				<?php echo ($sb_count == 0) ? $sb_count . '/' . sb_count($fixtureID,0) : '<a href="' . URL_replace($url,'sbff',$fixtureID) . '">' . $sb_count . '</a>/' . sb_count($fixtureID,0); ?>
				</p>

				<p>
				<?php echo ($bt_count == 0) ? $bt_count . '/' . bt_count($fixtureID,0) : '<a href="' . URL_replace($url,'bdff',$fixtureID) . '">' . $bt_count . '</a>/' . bt_count($fixtureID,0); ?>
				</p>

				</div>
			
			</div><?php
			
		}?>
		
		</div>
	<?php
	}?>
	</div>
?>