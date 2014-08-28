<?php
require_once 'core/init.php'; 
//print_r($_GET);

$fixID = $_GET['fixID'];
$esID = $_GET['esID'];
$htID = $_GET['htID'];
$atID = $_GET['atID'];

?>
<div class="col-md-12">

	<form role="form">

		<div class="col-md-5">
		<ul class="list-inline">
			<li>	
				<div class="form-group">
		    		<label for="htscoreht">Half Time Home</label>
		    		<select id="htscoreht_<?= $fixID ?>" class="htscoreht form-control"><?php second_dropDown() ?></select>
		  		</div>
			</li>

			<li>

			  <div class="form-group">
			    <label for="atscoreht">Half Time Away</label>
			    <select id="atscoreht_<?= $fixID ?>" class="atscoreht form-control"><?php second_dropDown() ?></select>
			  </div>

			</li>
		</ul>
		</div>
		<div class="col-md-5">
		<ul class="list-inline">
			<li>

			  <div class="form-group">
			    <label for="ht">Full Time Home</label>
			    <select name = "htscore" id="htscore_<?= $fixID ?>" class="htscore form-control"><?php second_dropDown() ?></select>
			  </div>

			 </li>
			 
			 <li>

			  <div class="form-group">
			    <label for="at">Full Time Away</label>
			    <select name = "htscore" id="atscore_<?= $fixID ?>" class="atscore form-control"><?php second_dropDown() ?></select>
			  </div>

			</li>
		</ul>
		</div>
		<div class="col-md-2">
		<button type="button" class="btn btn-default btn-addscore">Add</button>
		</div>


	    <input type="hidden" id="htID_<?= $fixID ?>" value="<?= $htID ?>">
	    <input type="hidden" id="atID_<?= $fixID ?>" value="<?= $atID ?>">
	    <input type="hidden" id="esID_<?= $fixID ?>" value="<?= $esID ?>">   

	</form>

</div>