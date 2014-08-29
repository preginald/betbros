<?php require_once 'core/init.php';

$bank = (isset($_POST['bank'])) ? $_POST['bank'] : '';
$stakingID = (isset($_POST['stakingID'])) ? $_POST['stakingID'] : '';
$fxdparam1 = (isset($_POST['fxd-param1'])) ? $_POST['fxd-param1'] : '';
$wlpparam1 = (isset($_POST['wlp-param1'])) ? $_POST['wlp-param1'] : ''; 
$wlpparam2 = (isset($_POST['wlp-param2'])) ? $_POST['wlp-param2'] : ''; 
$wlpparam3 = (isset($_POST['wlp-param3'])) ? $_POST['wlp-param3'] : ''; 
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