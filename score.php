<?php
protect_page(); 

$sportsID = mysql_real_escape_string($_GET['sid']);
$eventSeasonID = mysql_real_escape_string($_GET['esid']);
$fixtureID = mysql_real_escape_string($_GET['fixid']);
$htid = mysql_real_escape_string($_GET['htid']);
$atid = mysql_real_escape_string($_GET['atid']);

if (isset($_POST['add_score'])) {
	//print_r_pre($_POST);
	$htscore = mysql_real_escape_string($_POST['htscore']);
	$atscore = mysql_real_escape_string($_POST['atscore']);
    $htscoreht = mysql_real_escape_string($_POST['htscoreht']);
	$atscoreht = mysql_real_escape_string($_POST['atscoreht']);

	$createTimestamp = date("Y-m-d H:i:s");
    echo kickoff_check_insert($fixtureID,$session_user_id,$createTimestamp);
    echo halftime_check_insert($fixtureID,$session_user_id,$createTimestamp);
    echo fulltime_check_insert($fixtureID,$session_user_id,$createTimestamp);
    echo score_check_insert($fixtureID,$session_user_id,$createTimestamp,$htscore,$atscore,$htscoreht,$atscoreht);
}

if (isset($_GET['addscore']) && ($_GET['addscore']) == 1) {?>

    <h2>Final Score</h2>
    <form name="add-shot-home" method="post" action="" >
        <div>
        <label for="htscoreht">HT Home</label>
        <select name = "htscoreht" id="htscoreht" required><?php second_dropDown() ?></select>
        <label for="atscoreht">HT Away</label>
        <select name = "atscoreht" id="atscoreht" required><?php second_dropDown() ?></select>
        </div>
        <div>
        <label for="htscore">FT Home</label>
        <select name = "htscore" id="htscore" required><?php second_dropDown() ?></select>
        <label for="atscore">FT Away</label>
        <select name = "atscore" id="atscore" required><?php second_dropDown() ?></select>
        </div>
        <input type="submit" name="add_score" value="Add">
    </form>




<?php }

$hgoals = ft_hgoals($fixtureID); 
$agoals = ft_agoals($fixtureID);

?>

<table width="100%">
<tr>
<td width="45%" align="right"><strong style="font-size: 20px;">
<?php echo fixture_home_heading($fixtureID) ?>
</strong></td>
<td width="10%" align="center"><strong style="font-size: 20px;">
<a href="index.php?page=fixtures&view=detail&sid=<?php echo $sportsID;?>&esid=<?php echo $eventSeasonID ?>&fixid=<?php echo $fixtureID ?>&htid=<?php echo $htid ?>&atid=<?php echo $atid . score_url() ?>">
<?php echo $hgoals ?> :
<?php echo $agoals ?>
</a>
</strong></td>
<td width="45%"><strong style="font-size: 20px;">
<?php echo fixture_away_heading($fixtureID) ?>
</strong></td>
</tr>
</table>

<script>

$('#htscoreht').on('change',function(){
    var htscoreht = $(this).val();
    $('#htscore').val(htscoreht);
});

$('#atscoreht').on('change',function(){
    var atscoreht = $(this).val();
    $('#atscore').val(atscoreht);
});

</script>