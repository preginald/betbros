<?php require_once 'core/init.php'; ?>
<div>
<form role="form">
<label for="name">Label name</label>
<input type="text" name="name" id="name" maxlength="7" required>
<label for="bank">Bank size</label>
<input type="number" name="bank" id="bank" value="<?= $max ?>" min="1" max="<?= $max ?>" step="0.01">
<label for="staking" class="tb mr">Staking plan</label>
<select name="stakingID" id="staking" onchange="stakingParam()"><?php staking_dropdown() ?></select>

<div id="fxd">
	<label for="param1" class="tb">Fixed Amount</label>
	<input type="number" name="fxd-param1" id="param1" min="0.1" step=any>
</div>

<div id="wlp">
	<label for="param1" >Win %</label>
	<input type="number" name="wlp-param1" id="param1" min="0.1" step=any>
	<label for="param2" >Lose %</label>
	<input type="number" name="wlp-param2" id="param2" min="0.1" step=any>
	<div>
		<label for="param3" >Rounding</label><br/>
		<input type="radio" name="wlp-param3" id="param3" value="0" checked>No Rounding<br/>	
		<input type="radio" name="wlp-param3" id="param3" value="1">Round Down<br/>	
		<input type="radio" name="wlp-param3" id="param3" value="2">Round Up<br/><br/>	
	</div>
</div>

<div><label for="desc" >Description</label></div>
<div><textarea name="desc" id="desc"></textarea></div>
<input type="hidden" name="max" value="<?= $max ?>">
<input type="submit" name="add_label" value="Add">
</form>
</div>

<script type="text/javascript">
function stakingParam(){
	var stakingID=document.getElementById("staking").value;
	//alert(stakingID);
	if (stakingID==2) {
		document.getElementById('fxd').style.display = 'block';
		document.getElementById('wlp').style.display = 'none';
	} else if (stakingID==3) {
		document.getElementById('fxd').style.display = 'none';
		document.getElementById('wlp').style.display = 'block';
	} else if (stakingID==1){
		document.getElementById('fxd').style.display = 'none';
		document.getElementById('wlp').style.display = 'none';
	};
}
</script>