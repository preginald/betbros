<?php 

$ubkID = $_GET['bkID'];
$ubk = get_user_bookie($ubkID);
$depositURL = $url . "&bktrns=in";
$withdrawURL = $url . "&bktrns=out";
$returnURL = URL_replace_long($url,"&bkID=$ubkID","&bkID=0");
?>

<?php

if (isset($_POST['deposit_bnk_trans'])) {
    $desc = stripslashes($_POST['desc']);
    $in = stripslashes($_POST['add']);
    $bankID = stripcslashes($_POST['ubnk']);
    $bkID = stripcslashes($_POST['bkID']);
    $createUserID = stripslashes($_POST['createUserID']);
    $dt = $_POST['dt'];
    
    $createTimestamp = date("Y-m-d H:i:s");
	// need to add funds to related bookie account and then remove funds from related bank account
    $sql_in_bk_trans=
    "INSERT INTO `book_trans`(`bookieID`,`description`,`in`,`dt`,`createUserID`, `createTimestamp`) 
    VALUES ('$bkID','$desc','$in','$dt','$createUserID','$createTimestamp')";
    $result = mysql_query($sql_in_bk_trans);
    $bk_trans_ID = mysql_insert_id();
    $desc = "deposit to " . get_user_bookie($ubkID) . " ID: " . $bk_trans_ID;
    
    if($result){
        // echo "successfully added new label";
	//$header1 = URL_replace_long($url,"&bktrns=in",'');
        
        $sql_out_bnk_trans=
        "INSERT INTO `bank_trans`(`bankID`,`description`,`out`,`dt`,`createUserID`, `createTimestamp`) 
        VALUES ('$bankID','$desc','$in','$dt','$createUserID','$createTimestamp')";
        mysql_query($sql_out_bnk_trans);
        
	//header('Location: '. $header1);
    } else {
            echo "ERROR: Add funds insert";
    }	    
}
if (isset($_POST['withdraw_bnk_trans'])) {
    $desc = stripslashes($_POST['desc']);
    $out = stripslashes($_POST['add']);
    $bankID = stripcslashes($_POST['ubnk']);
    $bkID = stripcslashes($_POST['bkID']);
    $createUserID = stripslashes($_POST['createUserID']);
    $dt = $_POST['dt'];
    
    $createTimestamp = date("Y-m-d H:i:s");
	// need to remove funds to related bookie account and then add funds to related bank account
    $sql_out_bk_trans=
    "INSERT INTO `book_trans`(`bookieID`,`description`,`out`,`dt`,`createUserID`, `createTimestamp`) 
    VALUES ('$bkID','$desc','$out','$dt','$createUserID','$createTimestamp')";
    $result = mysql_query($sql_out_bk_trans);
    $bk_trans_ID = mysql_insert_id();
    $desc = "withdraw from " . get_user_bookie($ubkID) . " ID: " . $bk_trans_ID;
    
    if($result){
        // echo "successfully added new label";
	//$header1 = URL_replace_long($url,"&bktrns=out",'');
        
        $sql_in_bnk_trans=
        "INSERT INTO `bank_trans`(`bankID`,`description`,`in`,`dt`,`createUserID`, `createTimestamp`) 
        VALUES ('$bankID','$desc','$out','$dt','$createUserID','$createTimestamp')";
        mysql_query($sql_in_bnk_trans);
        
	//header('Location: '. $header1);
    } else {
            echo "ERROR: Add funds insert";
    }	   	    
}

?>

<div class="l w750 clear">
    <h2><?= $ubk ?> Transactions</h2>
<?php

$bankTransQuery = "SELECT * FROM `book_trans` WHERE `bookieID` = $ubkID AND `createUserID` = $user_id ORDER BY ID";
$result = mysql_query($bankTransQuery);
?> 
    <div class="l w750">
            <div class="l tb bb1 pt5 pb5 pt5">
            
                    <p class="l mr w100">Time Stamp</p>
                    <p class="l mr w400">Description</p>
                    <p class="l mr w100 ar">In</p>
                    <p class="l mr w100 ar">Out</p>
            </div>
            <?php
            while($row_bktrans = mysql_fetch_assoc($result)) {
                    $desc = $row_bktrans['description'];
                    $in = $row_bktrans['in'];
                    $out = $row_bktrans['out'];
                    $dt = nice_date($row_bktrans['dt'],"d M Y");

                    //$bnkPL = number_format(get_bk_PL($bkID,$uID),2);
                    //$bankTrans = number_format(get_bank_trans($bkID,$uID),2);
                    //$bnkBal = number_format(get_bank_bal($bnkID,$uID),2);

                    //print_r_pre($row_bnktrans);?>
                    <div class="l bb1 pt5 pt5 pb5 hv">

                    <p class="l mr w100"><?= $dt ?></p>
                    <p class="l mr w400"><?= $desc ?></p> 
                    <p class="l mr w100 ar"><?= $in ?></p> 
                    <p class="l mr w100 ar"><?= $out ?></p>
                    
                    </div>
                    <?php
                    //$tbnkBal += $bnkBal;
            }
            
            if(isset($_GET['bktrns'])){
                if(isset($_GET['bktrns']) && ($_GET['bktrns'] == 'in') || ($_GET['bktrns'] == 'out') ){
                    include 'user_bk_trans_form.php';
                }
            }else {?>
            <input type="button" value="Deposit" onClick="javascript:window.location.href='<?= $depositURL ?>'">
            <input type="button" value="Withdraw" onClick="javascript:window.location.href='<?= $withdrawURL ?>'">
            <input type="button" value="Cancel" onClick="javascript:window.location.href='<?= $returnURL ?>'">
            <?php
            }
            ?>
    </div>
</div>
