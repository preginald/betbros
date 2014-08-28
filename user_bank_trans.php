<?php 

$ubnkID = $_GET['bnkID'];
$ubnk = get_user_bank($ubnkID);
$returnURL = URL_replace_long($url,"&bnkID=$ubnkID","&bnkID=0");
$bnkformURL = $url . "&bnktrns=add";
$bnkbal
?>
<div class="l w750 clear">
    <h2><?= $ubnk ?> Transactions</h2>
<?php

$bankTransQuery = "SELECT * FROM `bank_trans` WHERE `bankID` = $ubnkID AND `createUserID` = $user_id";
$result = mysql_query($bankTransQuery);
?>
    <div class="l w750">
            <div class="l tb">
                    <p class="l mr w100">Time Stamp</p>
                    <p class="l mr w390">Description</p>
                    <p class="l mr w70 ar">In</p>
                    <p class="l mr w70 ar">Out</p>
                    <p class="l mr w70 ar">Balance</p>
            </div>
            <?php
            while($row_bnktrans = mysql_fetch_assoc($result)) {
                    $desc = $row_bnktrans['description'];
                    $in = number_format($row_bnktrans['in'],2);
                    $out = number_format($row_bnktrans['out'],2);
                    $dt = nice_date($row_bnktrans['dt'],"d M Y");
                    $bnkbal += $in;
                    $bnkbal -= $out;

                    //print_r_pre($row_bnktrans);?>
                    <div class="l bb1 pt5 pb5 hv">

                    <p class="l mr w100"><?= $dt ?></p>
                    <p class="l mr w390"><?= $desc ?></p> 
                    <p class="l mr w70 ar"><?= $in ?></p> 
                    <p class="l mr w70 ar"><?= $out ?></p>
                    <p class="l mr w70 ar"><?= number_format($bnkbal,2) ?></p>
                    </div>
                    <?php
                    //$tbnkBal += $bnkBal;
            }

            if(isset($_GET['bnktrns'])){
                if(isset($_GET['bnktrns']) && $_GET['bnktrns'] == 'add'){
                    include 'user_bank_trans_form.php';
                }
            }else {?>
                <input type="button" value="Add Funds" onClick="javascript:window.location.href='<?= $bnkformURL ?>'">
                <input type="button" value="Cancel" onClick="javascript:window.location.href='<?= $returnURL ?>'">
            <?php
            }
            ?>           
    </div>
</div>