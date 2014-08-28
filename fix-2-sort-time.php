<?php require_once 'core/init.php'; 

if (isset($_GET['option']) && $_GET['option'] == 'filter') {
	$val = sanitize($_GET['val']);
	$date = sanitize($_GET['date']);
	$where = (empty($_GET['val']) ? "" : " AND (t1.name LIKE '%$val%' OR t2.name LIKE '%$val%' OR e.name LIKE '%$val%' OR c.name LIKE '%$val%')");
	$where .= (empty($_GET['date']) ? "" : " AND fix.date = '$date' ");
}

$load = htmlentities(strip_tags($_GET['load'])) * 10;

$sql = 
"SELECT 
fix.ID,
u.username AS user,
e.ID as eid,
s.ID as sid,
fix.datetime,
-- fix.date, 
-- fix.time,

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

WHERE s.ID=2 $where

ORDER BY fix.datetime DESC

LIMIT ". $load. ",20 ";

$result = mysql_query($sql);
$nbr = mysql_num_rows($result);


$sID=2;

if ($nbr == 0 ) {
	echo "No more records";
} else {
	while($row = mysql_fetch_assoc($result)) {

		$datetime = read_datetime($row['datetime']);
		
		$htid = $row['ts1id'];
		$atid = $row['ts2id'];
		$sbType = $row['sid'];
		$fixID = $fixtureID = $row['ID'];
		$fixst = $row['fixstID'];
		$uname = $row['user'];
		
		$eventSeasonID = $eventID = $esID = $row['esID'];

		$bt_count = bt_count($fixtureID,$session_user_id);
		$sb_count = sb_count($fixtureID,$session_user_id);

		$t1name = $row['t1name'];
		$t2name = $row['t2name'];
	  	$cms = cm_count($fixtureID,0);

		?>
			
	<li class="fix-item" id="<?= $fixID ?>">
	<div class="panel-group" >
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-8">
						<ul class="list-inline">
							<!-- <li><?= $audate . " " . $time ?></li> -->
							<li><?= $datetime ?></li>
							<li><a href="index.php?page=teams&view=detail&sid=<?php echo $sID;?>&esid=<?php echo $eventSeasonID ?>&tsid=<?php echo $htid ?>"><?php echo $t1name ?></a></li>
							<li id="score_<?= $fixtureID ?>"><?= matchStatus_v2($fixst,$fixtureID) ?></li>
							<li><a href="index.php?page=teams&view=detail&sid=<?php echo $sID;?>&esid=<?php echo $eventSeasonID ?>&tsid=<?php echo $atid ?>"><?php echo $t2name ?></a></li>
						</ul>
					</div>
					<div class="col-md-4">
						<ul class="list-inline pull-right">
							<li>
								<button type="button" class="btn btn-default btn-sm fxmkbtn" data-toggle="collapse" data-target="#markets_<?= $fixtureID ?>" >
								<i class="fa fa-paw fa-lg"></i>
								</button>
							</li>
							<li>
								<button type="button" class="btn btn-default btn-sm fxselbtn" data-toggle="collapse" data-target="#sel_<?= $fixtureID ?>">
								<i class="fa fa-star fa-lg"></i>
								</button>
							</li>
							<li>
								<button type="button" class="btn btn-default btn-sm" >
								<i class="fa fa-comments-o fa-lg"></i>
								</button>
							</li>
							<li>
								<button type="button" class="btn btn-default btn-sm fxeditbtn" data-toggle="collapse" data-esID="<?= $esID ?>" data-htid="<?= $htid ?>" data-atid="<?= $atid ?>" data-target="#fixedit_<?= $fixtureID ?>"  >
								<i class="fa fa-pencil fa-lg"></i>
								</button>
							</li>						
							<li><button type="button" class="btn btn-default btn-sm fxdelbtn" data-fixID="<?= $fixtureID ?>" ><i class="fa fa-trash-o fa-lg"></i></button></li>
						</ul>
					</div>
				</div>
			</div>


			<div id="markets_<?= $fixtureID ?>" class="collapse">
	        		<i class="fa fa-spinner fa-spin fa-3x"></i>
	    	</div>

			<div id="sel_<?= $fixtureID ?>" class="collapse">
	        		<i class="fa fa-spinner fa-spin fa-3x"></i>
	    	</div>

			<div id="fixedit_<?= $fixtureID ?>" class="collapse">
	        		<i class="fa fa-spinner fa-spin fa-3x"></i>
	    	</div>

			<div class="panel-footer">
				<div class="row">
					<div class="col-md-12">
							<ul class="list-inline">
							<li><?= $fixtureID ?></li>
							<li><?= $uname ?></li>
							<li><?php echo ($sb_count == 0) ? $sb_count . '/' . sb_count($fixtureID,0) : '<a href="' . URL_replace($url,'sbff',$fixtureID) . '">' . $sb_count . '</a>/' . sb_count($fixtureID,0); ?></li>
							<li><?php echo ($bt_count == 0) ? $bt_count . '/' . bt_count($fixtureID,0) : '<a href="' . URL_replace($url,'bdff',$fixtureID) . '">' . $bt_count . '</a>/' . bt_count($fixtureID,0); ?></li>
							<li class="hidden-xs"><?= $row['ename'] ?></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>			
	</li>

	<?php
	}
}