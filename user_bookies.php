<?php

$bookiesarray = array( "addbk" => "");

foreach ($bookiesarray as $key => $value) {

	if (!isset($_GET[$key])) {
		$URL = $url . "&$key=1";
		$bookURL = '<a href="' . $URL  . '">+' . $value . '</a>';
	 	
	 } elseif (isset($_GET[$key]) && $_GET[$key] == 0) {
		$URL = str_replace("&$key=0","&$key=1",$url);
		$bookURL = '<a href="' . $URL  . '">+' . $value . '</a>';
	}else{
		$URL = str_replace("&$key=1","&$key=0",$url);
		$bookURL = '<a href="' . $URL  . '">x' . $value . '</a>';
	}
}

if(isset($_POST['add_user_bk'])){
        $bkID = $_POST['bkID'];
        $uname = $_POST['uname'];
        $password = $_POST['password'];
        $createUserID = $_POST['createUserID'];
        
        $createTimestamp = date("Y-m-d H:i:s");
        
    $sql_add_bk =
    "INSERT INTO `userBookies`(`bookieID`,`username`,`password`,`userID`,`active` ,`createTimestamp`) 
    VALUES ('$bkID','$uname','$password','$createUserID','1','$createTimestamp')";
    $result = mysql_query($sql_add_bk);
    
    if($result){
        // echo "successfully added new label";
	//$header = URL_replace_long($url,"&addbk=1",'');
        
	//header('Location: '. $header);
    } else {
            echo "ERROR: Add bookie insert";
    }	           

}
?>

<div class="l w470 clear">
    <h2>Bookies <?= $bookURL ?></h2>
    
    <?php
    if(isset($_GET['addbk']) && $_GET['addbk'] == 1){
        echo '<div class="l clear bbl p5">';
        include 'user_bk_form.php';
        echo '</div>';
}

$tbkBal = number_format(0,2);



$userBookiesQuery = "SELECT ubk.userID uID, bk.ID bkID, bk.name bkname, ubk.ID ubkID, ubk.username bkuname, ubk.active bkactive FROM userBookies ubk
					INNER JOIN users u ON u.user_id = ubk.userID
					INNER JOIN bookies bk ON bk.ID = ubk.bookieID
					WHERE ubk.userID = $user_id";
			
$result = mysql_query($userBookiesQuery);

?>
    <div class="l w750">
            <div class="l tb bb1">
                    <p class="l mr w100">Bookie</p>
                    <p class="l mr w200">Username</p>
                    <p class="l mr w20">Act</p>
                    <p class="l mr w70 ar">Stake</p>
                    <p class="l mr w70 ar">Returns</p>                    
                    <p class="l mr w70 ar">P/L</p>
                    <p class="l mr w70 ar">Tranfers</p>
                    <p class="l mr w70 ar">Balance</p>
            </div>
            <?php
            while($row = mysql_fetch_assoc($result)) {
                    $ubkID = $row['ubkID'];
                    $bkID = $row['ubkID'];
                    $uID = $row['uID'];
                    $bkname = $row['bkname'];
                    $bkuname = $row['bkuname'];
                    $bkactive = $row['bkactive'];

                    $bkStake = number_format(get_bk_stake($bkID,$uID),2);
                    $bkReturns = number_format(get_bk_returns($bkID,$uID),2);
                    
                    $bkPL = number_format(get_bk_PL($bkID,$uID),2);
                    $bookTrans = number_format(get_book_trans($bkID,$uID),2);
                    $bkBal = number_format($bkPL + $bookTrans,2);

                    
                    if(isset($_GET['bkID'])){
                        $GETubkID = $_GET['bkID'];
                        $bkURL = URL_replace_long($url,"&bkID=$GETubkID","&bkID=$ubkID");
                    }else{
                        $bkURL = $url . "&bkID=$ubkID";
                    }

                    //print_r_pre($row);?>
                    <div class="l w750 bb1 pt5 pb5 hv">

                    <p class="l mr w100"><a href="<?= $bkURL ?>"><?= $bkname ?></a></p> 
                    <p class="l mr w200"><?= $bkuname ?></p> 
                    <p class="l mr w20"><?= $bkactive ?></p>
                    <p class="l mr w70 ar"><?= $bkStake ?></p>
                    <p class="l mr w70 ar"><?= $bkReturns ?></p>                    
                    <p class="l mr w70 ar"><?= $bkPL ?></p>
                    <p class="l mr w70 ar"><?= $bookTrans ?></p>
                    <p class="l mr w70 ar"><?= $bkBal ?></p>
                    </div>
                    <?php
                    //$tbkBal += $bkBal;
                    
            }

            $tbkStake = number_format(get_tbk_stake($uID),2);
            $tbkReturns = number_format(get_tbk_returns($uID),2);
            $tbkPL = number_format(get_tbk_PL($uID),2);
            $tbkTrans = number_format(get_tbk_trans($uID),2);
            $tbkBal = number_format(get_total_book_bal($uID),2);

            ?>
            <div class="r bb1 pt5">
                    <p class="l mr w70 ar tb"><?= $tbkStake ?></p>
                    <p class="l mr w70 ar tb"><?= $tbkReturns ?></p>
                    <p class="l mr w70 ar tb"><?= $tbkPL ?></p>
                    <p class="l mr w70 ar tb"><?= $tbkTrans ?></p>
                    <p class="l mr w70 ar tb"><?= $tbkBal ?></p>
            </div>
    </div>
</div>
