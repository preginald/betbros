<?php require_once 'core/init.php'; 

if (isset($_GET['esID'])) {
	$esID = $_GET['esID'];
	$where = " AND es.ID = $esID ";
}

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

WHERE s.ID=2 $where

ORDER BY fix.date DESC, fix.time DESC

LIMIT 20
";

$result = mysql_query($sql);

$encode = array();
while($row = mysql_fetch_assoc($result)) {
	{
		$new = array(
			'audate' => date('D d-M', strtotime($row['date'])),
			'time' => date('g:i a', strtotime($row['time'])),
			'htid' => $row['ts1id'],
			'atid' => $row['ts2id'],
			'sbType' => $row['sid'],
			'fixID' => $fixID = $row['ID'],
			'fixst' => $row['fixstID'],
			'uname' => $row['user'],
			
			'esID' => $row['esID'],

			'bt_count' => bt_count($fixID,$session_user_id),
			'sb_count' => sb_count($fixID,$session_user_id),

			't1name' => $row['t1name'],
			't2name' => $row['t2name'],
		  	'cms' => cm_count($fixID,0)
		  	);
		$encode[] = $new;
	}
}
echo json_encode($encode);
//echo '[{"audate":"Mon 03-Mar","time":"1:00 am","htid":"163","atid":"164","sbType":"2","fixID":"860","fixst":"4","uname":"noops","esID":"59","bt_count":"0","sb_count":"0","t1name":"White Star Bruxelles","t2name":"Westerlo","cms":"0"}]';