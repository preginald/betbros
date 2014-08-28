<form role="form">
	<div class="form-group">
		<div class="row">
			<div class="col-md-4">
				<select class="form-control"  name="cID" id="cID"><?php region_dropDown() ?></select>
			</div>
			<div class="col-md-4">
				<select class="form-control"  name="event" id="event" ></select>
			</div>
			<div class="col-md-4">
				<select class="form-control"  name="eventSeason" id="eventSeason" ></select>
			</div>
		</div>
	</div>
</form>

<div id="view_e"></div>

<div id="view_es"></div>

<div id="view_ts"></div>

<div id="fix_div"></div>

<script id="es-view-template" type="text/x-handlebars-template">
	<div class="well">
		<div class="row">
			<div class="col-xs-3 col-md-2">
				ID: {{ID}}
			</div>
			<div class="col-xs-9 col-md-4">
				Event: {{name}}
			</div>
			<div class="col-xs-6 col-md-3">
				Start: {{startDate}}
			</div>
			<div class="col-xs-6 col-md-3">
				End: {{endDate}}
			</div>
		</div>
	</div>
</script>

<script id="e-list-template" type="text/x-handlebars-template">
	<li><span class="label label-default">{{name}}</span></li>
</script>
<script id="addEventSQLStatus" type="text/x-handlebars-template">
	<div class="alert alert-success alert-dismissible" role="alert">
	  		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	 		<strong>SUCCESS!</strong>  Added {{e}} with ID {{ID}}.
	</div>
</script>

<script id="addEventSeasonSQLStatus" type="text/x-handlebars-template">
	<div class="alert alert-success alert-dismissible" role="alert">
	  		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	 		<strong>SUCCESS!</strong>  Added {{e}} with ID {{ID}}.
	</div>
</script>

<script id="addEventSeasonFormError" type="text/x-handlebars-template">
	<div class="alert alert-warning alert-dismissible" role="alert">
	  		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	 		<strong>Warning!</strong>  Event end date must be greater than event start date.
	</div>
</script>

<script id="newTeamSeasonAdded" type="text/x-handlebars-template">
	<li class="list-group-item list-group-item-warning" id="{{ID}}">
		<button type="button" class="btn btn-info btn-sm dedupbtn" data-tsid="{{ID}}">{{ID}}</button>
	{{t}}
	<span class="label label-default">{{sname}}</span>
	<span class="label label-default">{{lname}}</span>

	</li>
</script>


<script id="updateTeam" type="text/x-handlebars-template">

</script>

<script type="text/javascript" src="js/events.js"></script>
<script type="text/javascript" src="js/fix-2.js"></script>