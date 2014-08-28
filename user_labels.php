<?php
//print_r($_POST);
require_once 'core/init.php'; 

$labelsarray = array( "addlabels" => "");

foreach ($labelsarray as $key => $value) {

	if (!isset($_GET[$key])) {
		$URL = $url . "&$key=1";
		$labelURL = '<a href="' . $URL  . '">+' . $value . '</a>';
	 	
	 } elseif (isset($_GET[$key]) && $_GET[$key] == 0) {
		$URL = str_replace("&$key=0","&$key=1",$url);
		$labelURL = '<a href="' . $URL  . '">+' . $value . '</a>';
	}else{
		$URL = str_replace("&$key=1","&$key=0",$url);
		$labelURL = '<a href="' . $URL  . '">x' . $value . '</a>';
	}
}

$bank_label = get_bank_label($session_user_id);
$total_bank = get_total_bank_bal($session_user_id);
$tbkBal = get_total_book_bal($session_user_id);
$max = $tbkBal + $total_bank - $bank_label;

?>

<h2>Labels</h2>
<button type="button" class="btn btn-default" id="addlabelbtn"><i class="fa fa-plus-circle fa-lg"></i></button>
<div class="row" id="labelsForm" style="display: none;">
	<div class="panel panel-default">
  		<div class="panel-body">
			<form role="form">

			<div class="row">
		        <div class="form-group col-md-2">
					<label for="name">Label name</label>
					<input class="form-control" type="text" id="name" maxlength="7" required>
		        </div>
		        
		        <div class="form-group col-md-2">
					<label for="bank">Bank size</label>
					<input class="form-control" type="number" id="bank" value="<?= $max ?>" min="1" max="<?= $max ?>" step="0.01">
		        </div>
		      
		        <div class="form-group col-md-2">
					<label for="staking" class="tb mr">Staking plan</label>
					<select class="form-control" id="stakingID" ><?php staking_dropdown() ?></select>
		        </div>
				<div class="form-group col-md-2 fxd" style="display: none;">
					<label for="param1" class="tb">Fixed Amount</label>
					<input class="form-control" type="number" id="fxd-param1" min="0.1" step=any>
				</div>

				<div class="form-group col-md-2 wlp" style="display: none;">
					<label for="param1" >Win %</label>
					<input class="form-control" type="number" id="wlp-param1" min="0.1" step=any>
				</div>

				<div class="form-group col-md-2 wlp" style="display: none;">
					<label for="param2" >Lose %</label>
					<input class="form-control" type="number" id="wlp-param2" min="0.1" step=any>
				</div>
		    </div>

		    <div class="row">
				<div class="form-group col-md-6 wlp" style="display: none;">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-primary active">
					    <input type="radio" name="wlp-param3" id="wlp-option1" value="0" checked>No Rounding
						</label>
						<label class="btn btn-primary">
						<input type="radio" name="wlp-param3" id="wlp-option2" value="1">Round Down
						</label>
						<label class="btn btn-primary">
						<input type="radio" name="wlp-param3" id="wlp-option3" value="2">Round Up
						</label>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label for="desc" >Label Description</label>
					<textarea class="form-control" name="desc" id="desc"></textarea>
				</div>
			</div>
				<input type="hidden" id="max" value="<?= $max ?>">
				<br/>
			<div class="row">
				<div class="col-md-6">
					<button type="button" class="btn btn-default addlabel">Add</button>
				</div>
			</div>
			</form>
  		</div>
	</div>
</div>


	<ul class="list-inline ">
		<li>Date created</li>
		<li>Label</li>
		<li>Staking</li>
		<li>Act</li>
		<li>St Bank</li>
		<li>Stake</li>
		<li>Returns</li>
		<li>PL</li>
		<li>Balance</li>
	</ul>
	<?php 

	$get_label_array_default = get_label_sum(1,$session_user_id);
	$default_lstake = $get_label_array_default['lstake'];
	$default_lreturns = $get_label_array_default['lreturns'];
	$default_lPL = $get_label_array_default['lpl'];

	?>
	
		
	<ul>
		<li>
		<div class="panel panel-default">
		  <div class="panel-heading">
			<h3 class="panel-title" >Default</h3>
		  </div>
		  <div class="panel-body">
			<ul class="list-inline">
				<li>NA</li>
				<li>undefined</li>
				<li>1</li>
				<li>NA</li>
				<li><?= $default_lstake ?></li>
				<li><?= $default_lreturns ?></li>
				<li><?= $default_lPL ?></li>
				<li>NA</li>			
			</ul>
		  </div>
		</div>
		</li>

	<?php
	$result = labels_table($session_user_id,0);
	while ($row = mysql_fetch_assoc($result)) {
		$lID = $row['lID'];
		$label = $row['name'];
		$staking = $row['st'];
		$desc = $row['desc'];
		$bank = $row['bank'];
		$act = $row['active'];
		$dt = nice_date($row['dt'],"d M Y");
		$get_label_array = get_label_sum($lID,$session_user_id);
		$lstake = $get_label_array['lstake'];
		$lreturns = $get_label_array['lreturns'];
		$lPL = $get_label_array['lpl'];
		$lbalance = number_format($bank + $lPL,2);

		$tlstake += $lstake;
		$tlreturns += $lreturns;
		$tlPL += $lPL;
		$tlbalance += $lbalance;

		?>
		<li>
		<div class="panel panel-default">
		  <div class="panel-heading">
			<h3 class="panel-title" ><?= $label ?></h3>
		  </div>
		  <div class="panel-body">
			<ul class="list-inline ">
				<li><?= $dt ?></li>
				<li><?= $staking ?></li>
				<li><?= $act ?></li>
				<li><?= $bank ?></li>
				<li><?= $lstake ?></li>
				<li><?= $lreturns ?></li>
				<li><?= $lPL ?></li>
				<li><?= $lbalance ?></li>			
			</ul>
		  </div>
		</div>
		</li>
		<?php	
	}
	$tlstake += $default_lstake;
	$tlreturns += $default_lreturns;
	$tlPL += $default_lPL;

	?>
	</ul>

        <div class="r bb1 pt5">
                <p class="l mr w70 ar tb"><?= number_format($tlstake,2) ?></p>
                <p class="l mr w70 ar tb"><?= number_format($tlreturns,2) ?></p>
                <p class="l mr w70 ar tb"><?= number_format($tlPL,2) ?></p>
                <p class="l mr w70 ar tb"><?= number_format($tlbalance,2) ?></p>
        </div>		
