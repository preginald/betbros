<?php require_once 'core/init.php'; ?>
<?php
if (isset($_GET['option']) && $_GET['option'] == 'cID' && !empty($_GET['ID'])) {
	$cID = sanitize($_GET['ID']);
	$s =
		"SELECT t.ID AS ID, t.name AS tname, t.sname AS tsname, t.lname AS tlname, r.name AS rname, r.alpha_2 AS ralpha
		FROM teams AS t 
		INNER JOIN region AS r ON r.id=t.countryID 
		WHERE r.ID = $cID
		ORDER BY t.name ASC";
} elseif (isset($_GET['option']) && $_GET['option'] == 'esID') {
	$esID = sanitize($_GET['ID']);
	// query to get list of sports names for heading
	$s =
		"SELECT
			ts.ID AS ID,
			t.ID AS tid,
			ts.ID AS tsid,
			t.name AS tname,
			t.sname AS tsname,
			t.lname AS tlname,
			e.name AS ename,
			es.ID esID,
			es.startDate AS sdate,
			es.endDate AS edate,
			man.firstName AS manfname,
			man.lastName AS manlname,
			cap.firstName AS capfname,
			cap.lastName AS caplname,

			kit.name AS kname,
			spn.name AS tspnname,

			c.name AS cname,
			c.alpha_2 AS calpha,
			ts.createTimestamp AS crtime
			FROM teamSeason AS ts
			INNER JOIN teams AS t
			ON ts.teamID=t.ID
			INNER JOIN eventSeason AS es
			ON es.ID=ts.eventSeasonID
			INNER JOIN events AS e
			ON e.ID = es.eventsID
			INNER JOIN person AS man
			ON man.ID=ts.managerID
			INNER JOIN person AS cap
			ON cap.ID=ts.captainID

			INNER JOIN brands AS kit 
			ON ts.kitID=kit.ID

			INNER JOIN brands AS spn
			ON ts.sponsorID=spn.ID
			
			INNER JOIN countries AS c
			ON c.id=e.countryID

			WHERE es.ID = $esID

			ORDER BY t.name ASC
			";
} elseif (condition) {
	# code...
}

$result = mysql_query($s); ?>

<?php if (isset($_GET['option']) && $_GET['option'] == 'esID') { ?>

	<div class="well">
		<form role="form">
			<div class="row">
			  	<div class="col-lg-4">
					<input type="text" id="teamSeason" class="form-control" placeholder="Team Name">
					<div class="list-group hidden" id="teamSeasonDropdown"></div>
				</div>
				<div class="col-lg-3 hidden extra">
					<input type="text" id="sname" class="form-control" placeholder="Short Name">
				</div>

				<div class="col-lg-3 hidden extra">
					<input type="text" id="lname" class="form-control" placeholder="Long Name">
				</div>

				<div >
					<button class="btn btn-default" id="btn-addteamSeason" type="button">Add Team</button>
				</div>

	


			</div><!-- /.row -->
			<input type="hidden" name="tID" id="tID">
			<input type="hidden" name="esID" id="esID" value="<?= $esID ?>">
		</form>
	</div>
<?php } ?>

	<ul class="list-group" id="teams">
	<?php
	if (!empty($_GET['ID'])) {
		while($row=mysql_fetch_assoc($result)){
			$esID = $row['esID'];
			?>
			<li class="list-group-item team" id="<?= $row['ID'] ?>">
				<div class="row">
					<div class="col-xs-8">
						<ul class="list-inline">
							<li><span class = "tname"><?php echo $row['tname'] ?></span></li>
							<li><span class="label label-default"><?php echo $row['tsname'] ?></span></li>
							<li><span class="label label-default"><?php echo $row['tlname'] ?></span></li>
						</ul>
					</div>
					<div class="col-xs-4 pull-right hidden text-right">
						<ul class="list-inline">
							<!-- <li><a class="dedupbtn" data-tsID="<?= $row['ID'] ?>"><?= $row['ID'] ?></a></li> -->
							<li><a class="delete-copy" href="#"><i class="fa fa-files-o"></i></a></li>
							<li><a class="edit-team" href="#"><i class="fa fa-pencil-square-o"></i></a></li>
						</ul>
					</div>
				</div>
			</li>
			<?php 
		}
	}
	?>
	</ul>

<script>
</script>