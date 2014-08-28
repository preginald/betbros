<div class="l clear bbl p5 w640">
<?php 
$ubkID = $_GET['bkID'];
$returnURL = URL_replace_long($url,"&bkID=1","&bkID=0");

if (isset($_GET['bktrns']) && $_GET['bktrns'] == "in"){
    $returnURL = URL_replace_long($url,"&bktrns=in","");
    ?>
<h2>Deposit Funds</h2>
<form name="in-bk-trans" method="post" action="">
    <div class="clear">
		<label for="ubnk" class="tb mr">From</label>
		<select name="ubnk" id="ubnk" required ><?php user_bank_dropdown($ubnk,$user_id) ?></select>
		<b>To</b>
		<?= $ubk ?>
	<br/>	
        <label for="desc" class="tb mr">Desc</label>
        <input class="w200" type="text" name="desc" id="desc" required>
        <br/>
        <label for="add" class="tb mr">Amt</label>
        <input class="w100 ar" type="number" name="add" id="add" min="1" max="<?= get_total_bank_bal($user_id) ?>" step="any" required>
        <label for="dt" class="tb mr">When?</label>
        <input type="date" name="dt" id="dt" required>
        <input type="hidden" name="bkID" value="<?= $ubkID ?>">
        <input type="hidden" name="createUserID" value="<?= $user_id ?>">
	<input type="submit" name="deposit_bnk_trans" value="Deposit">
        <input type="button" value="Cancel" onClick="javascript:window.location.href='<?= $returnURL ?>'">
    </div>
</form>
<?php
}elseif (isset($_GET['bktrns']) && $_GET['bktrns'] == "out"){
    $returnURL = URL_replace_long($url,"&bktrns=out","");
    $max = get_book_bal($ubkID,$user_id)
            ?>
<h2>Withdraw Funds</h2>
<form name="out-bk-trans" method="post" action="">
    <div class="clear">
        <div><span class="tb">From</span> <?= $ubk ?>
            <label for="ubnk" class="tb mr">To</label>
            <select name="ubnk" id="ubnk" required ><?php user_bank_dropdown($ubnk,$user_id) ?></select>

            <label for="desc" class="tb mr">Desc</label>
            <input class="w200" type="text" name="desc" id="desc" required>
        </div>
        <div>
            <label for="add" class="tb mr">Amt</label>
            <span class="f10">(Max $<?= $max?>)</span>
            <input class="w100 ar" type="number" name="add" id="add" min="1" max="<?= $max ?>" step="any" required>
            <label for="dt" class="tb mr">When?</label>
            <input type="date" name="dt" id="dt" required>
            <input type="submit" name="withdraw_bnk_trans" value="Withdraw">
            <input type="button" value="Cancel" onClick="javascript:window.location.href='<?= $returnURL ?>'">
        </div>
    </div>
    <input type="hidden" name="bkID" value="<?= $ubkID ?>">
    <input type="hidden" name="createUserID" value="<?= $user_id ?>">
</form>
<?php
}
?>
</div>