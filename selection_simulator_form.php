<?php require_once 'core/init.php';

$bank = (isset($_POST['bank'])? $_POST['bank'] : '');
$stakingID = (isset($_POST['stakingID'])? $_POST['stakingID'] : ''); 
$fxdparam1 = (isset($_POST['fxd-param1'])? $_POST['fxd-param1'] : '');
$wlpparam1 = (isset($_POST['wlp-param1'])? $_POST['wlp-param1'] : '');
$wlpparam2 = (isset($_POST['wlp-param2'])? $_POST['wlp-param2'] : ''); 
$wlpparam3 = (isset($_POST['wlp-param3'])? $_POST['wlp-param3'] : '');
?>
<div class="row">
	<div class="panel panel-default">
  		<div class="panel-body">
			<form role="form" >
				<div class="row">
					<div class="form-group col-md-2">
						<label for="label" >Label</label>
						<select multiple class="form-control" id="label">
							<?php user_label_dropdown($session_user_id,0,1) ?>
						</select>
					</div>

					<div class="form-group col-md-2">
						<label for="staking" >Staking plan</label>
						<select class="form-control"  name="stakingID" id="staking" ><?php staking_dropdown() ?></select>
					</div>

					<div class="form-group col-md-2">
						<label for="bank" >Start Bank</label>
						<input class="form-control" type="number" name="bank" id="bank" value="<?= $bank ?>" min="1" step="0.01">
					</div>
					<div class="form-group col-md-2 fst">
						<label for="stake" >First Stake</label>
						<input class="form-control" type="number" name="stake" id="stake" min="1" step="0.01">
					</div>

					<div class="form-group col-md-2 fxd">
						<label for="param1" >Fixed Amount</label>
						<input class="form-control"  type="number" name="fxd-param1" id="fxd-param1" min="0.1" step=any value="<?= $fxdparam1 ?>">
					</div>

					<div class="form-group col-md-2 wlp">
						<label for="param1" class="tb">Win %</label>
						<input class="form-control"  type="number" name="wlp-param1" id="wlp-param1" min="0.1" step=any>
					</div>
					<div class="form-group col-md-2 wlp">
						<label for="param2" class="tb">Lose %</label>
						<input class="form-control"  type="number" name="wlp-param2" id="wlp-param2" min="0.1" step=any>
					</div>
					<div class="form-group col-md-4 wlp">
						<label for="param3" class="tb">Rounding</label><br/>
						<label class="radio-inline">
							<input type="radio" name="wlp-param3" id="wlp-param3-0" value="0" checked>No
						</label>
						<label class="radio-inline">
							<input type="radio" name="wlp-param3" id="wlp-param3-1" value="1">Down
						</label>
						<label class="radio-inline">
							<input type="radio" name="wlp-param3" id="wlp-param3-2" value="2">Up
						</label>
					</div>
					<div class="form-group col-md-2 rst">
						<label for="param1">Threshold %</label>
						<input class="form-control" type="number" name="rst-param1" id="rst-param1" min="0.1" step=any>
					</div>
					<div class="form-group col-md-2 rst">
						<label for="param2">Stake %</label>
						<input class="form-control" type="number" name="rst-param2" id="rst-param2" min="0.1" step=any>
					</div>	
				</div>
				<button type="button" class="btn btn-default exec">Go</button>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">

var stakingID=$('#staking').val(),
	fst = 	$('.fst'),
	fxd =	$('.fxd'),
	wlp =	$('.wlp'),
	rst =	$('.rst');

$(function(){
		fxd.hide();
		wlp.hide();
		rst.hide();
});

$('body').on('change','#staking',function(){
	var stakingID = $(this).val();
	
	if (stakingID==2) {
		fst.hide();
		fxd.show();
		wlp.hide();
		rst.hide();
	} else if (stakingID==3) {
		fxd.show();
		fxd.hide();			
		wlp.show();			
		rst.hide();			
	} else if (stakingID==1){
		fst.show();
		fxd.hide();			
		wlp.hide();			
		rst.hide();			
	} else if (stakingID==4){
		fst.hide();
		fxd.hide();
		wlp.hide();
		rst.show();		
	};	
})
</script>