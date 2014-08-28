		<form name="fixture-date-filter" method="get" action="" >

		<input type="hidden" name="page" value="fixtures" />
		<input type="hidden" name="view" value="list" />
		<input type="hidden" name="sid" value="<?=$sportsID?>" />
		<input type="hidden" name="esid" value="<?=$eventSeasonID?>" />

		Start Date: <input type="date" name="startdate" placeholder="startdate" required>
		End Date: <input type="date" name="enddate" placeholder="enddate" required>
		<input type="submit" name="filter" value="custom">
		</form>