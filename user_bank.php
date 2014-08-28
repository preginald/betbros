<div class="l w640 clear">
    <h2>Bank</h2>
<?php 
$tbnkBal = number_format(0,2);

$userBankQuery = "SELECT ubnk.userID uID, bnk.ID bnkID, bnk.name bnkname, ubnk.username bnkuname, ubnk.active bnkactive 
                FROM user_bank ubnk
                INNER JOIN users u ON u.user_id = ubnk.userID
                INNER JOIN bank bnk ON bnk.ID = ubnk.bankID
                WHERE ubnk.userID = $user_id ORDER BY ubnk.ID";
			
$result = mysql_query($userBankQuery);

?>
    <div class="l w640">
            <div class="l tb bb1 pt5 pb5">
                    <p class="l mr w100">Bank</p>
                    <p class="l mr w100">Username</p>
                    <p class="l mr w50">Act</p>
                    <p class="l mr w80 ar">Balance</p>
            </div>
            <?php
            while($row_bank = mysql_fetch_assoc($result)) {
                    $bnkID = $row_bank['bnkID'];
                    $uID = $row_bank['uID'];
                    $bnkname = $row_bank['bnkname'];
                    $bnkuname = $row_bank['bnkuname'];
                    $bnkactive = $row_bank['bnkactive'];
                    
                    if(isset($_GET['bnkID'])){
                        $GETubnkID = $_GET['bnkID'];
                        $bnkURL = URL_replace_long($url,"&bnkID=$GETubnkID","&bnkID=$bnkID");
                    }else{
                        $bnkURL = $url . "&bnkID=$bnkID";
                    }        

                    //$bnkPL = number_format(get_bk_PL($bkID,$uID),2);
                    //$bankTrans = number_format(get_bank_trans($bkID,$uID),2);
                    $bnkBal = number_format(get_bank_bal($bnkID,$uID),2);

                    //print_r_pre($row_bank);?>
                    <div class="l bb1 pt5 pb5">

                    <p class="l mr w100"><a href="<?= $bnkURL ?>"><?= $bnkname ?></a></p> 
                    <p class="l mr w100"><?= $bnkuname ?></p> 
                    <p class="l mr w50"><?= $bnkactive ?></p>
                    <p class="l mr w80 ar"><?= $bnkBal ?></p>
                    </div>
                    <?php
                    $tbnkBal += $bnkBal;
            }?>
            <div class="l ml190">
                    <p class="l mr w80 tb ar">Total bal</p>
                    <p class="l mr w80 ar"><?= number_format($tbnkBal,2) ?></p>
            </div>
    </div>
</div>