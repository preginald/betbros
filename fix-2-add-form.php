<?php require_once 'core/init.php'; ?>

<div class="col-md-12">
<form role="form">
<ul class="list-inline">

<li>
  <div class="form-group">
    <label for="esID">League</label>
    <select class="form-control" id="esID" required> <?php event_season_dropDown(2,$esID) ?></select>
  </div>
</li>

<li>
  <div class="form-group">
    <label for="date">Kick Off Date</label>
    <input type="date" class="form-control" id="date" >
  </div>
</li>

<li>
  <div class="form-group">
    <label for="time">Kick Off Time</label>
    <input type="time" class="form-control" id="time" >
  </div>
</li>

<li>
  <div class="form-group">
    <label for="ht">Home Team</label>
    <input type="text" class="form-control" id="ht" placeholder="Home Team">
  </div>
 </li>
 
 <li>
  <div class="form-group">
    <label for="at">Away Team</label>
    <input type="text" class="form-control" id="at" placeholder="Away Team">
  </div>
</li>
</ul>
    <input id="htID" type="hidden" name="hdID">
    <input id="atID" type="hidden" name="atID">

    <input type="hidden" id="esID" value="<?= $esID ?>">

    <button type="button" class="btn btn-default btn-addfix">Add</button>

  </form>
</div>