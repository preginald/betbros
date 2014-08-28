<div class="l clear bbl p5 w640">
<?php

$returnURL = URL_replace_long($url,"&bnktrns=add","");

if (isset($_POST['add_bnk_trans'])) {
    $desc = stripslashes($_POST['desc']);
    $in = stripslashes($_POST['add']);
    $bankID = stripcslashes($_POST['bankID']);
    $createUserID = stripslashes($_POST['createUserID']);
    $dt = $_POST['dt'];
    
    $createTimestamp = date("Y-m-d H:i:s");

    $sql_add_bnk_trans=
    "INSERT INTO `bank_trans`(`bankID`,`description`,`in`,`dt`,`createUserID`, `createTimestamp`) 
    VALUES ('$bankID','$desc','$in','$dt','$createUserID','$createTimestamp')";
    $result = mysql_query($sql_add_bnk_trans);
    if($result){
            // echo "successfully added new label";
		echo $header = URL_replace_long($url,"&bnktrns=add",'');
		header('Location: '. $header);
    } else {
            echo "ERROR: Add funds insert";
    }	    
}

?>
<h2>Add Funds</h2>
<form name="add-bnk-trans" method="post" action="">
    <div class="clear">
        <div>
            <label for="desc" class="tb mr">Desc</label>
            <input class="w100" type="text" name="desc" id="desc" required>
        </div>
        <div>
            <label for="add" class="tb mr">Amt</label>
            <input class="w100 ar" type="number" name="add" id="add" required>
            <label for="dt" class="tb mr">When?</label>
            <input type="date" name="dt" id="dt" required>
        </div>
        <input type="hidden" name="bankID" value="<?= $ubnkID ?>">
        <input type="hidden" name="createUserID" value="<?= $user_id ?>">
	<input type="submit" name="add_bnk_trans" value="Add">
        <input type="button" value="Cancel" onClick="javascript:window.location.href='<?= $returnURL ?>'">
    </div>
</form>
</div>