<?php 
//print_r_pre($_POST);

$session_user_id = $user_data['user_id'];
$cmID = '';

if(isset($_POST['add_comment'])){
	
	$comment =$_POST['comment'];
	$createTimestamp = date("Y-m-d H:i:s");
	$sportsID = sanitize($_POST['sID']);
	$fixtureID = sanitize($_POST['fixtureID']);
	
	$sql1 = 
	"INSERT INTO `cm_fixture` (`sID`,`fixtureID`,`body`,`createuserID`,`createTimestamp`) 
	VALUES ($sportsID,$fixtureID,'$comment',$session_user_id, '$createTimestamp')";
	$result = mysql_query($sql1);
	if($result){
	} else {
		echo "ERROR: add comment";
	}
}

if(isset($_POST['reply_comment'])){
	
	$comment =$_POST['comment'];
	$createTimestamp = date("Y-m-d H:i:s");
	$cmr = sanitize($_POST['cmr']);
	$sportsID = sanitize($_POST['sID']);
	$fixtureID = sanitize($_POST['fixtureID']);

	
	$sql1 = 
	"INSERT INTO `cm_fixture` (`replyID`,`sID`,`fixtureID`,`body`,`createuserID`,`createTimestamp`) 
	VALUES ($cmr,$sportsID,$fixtureID,'$comment',$session_user_id, '$createTimestamp')";
	$result = mysql_query($sql1);
	if($result){
	} else {
		echo "ERROR: reply comment";
	}
}

if(isset($_POST['edit_comment'])){
	$cmID = sanitize($_POST['cme']);
	$comment = sanitize($_POST['comment']);
	$modifyTimestamp = date("Y-m-d H:i:s");

	
	$sql3 = 
	"UPDATE `cm_fixture` SET `body`='$comment', `modifyUserID`=$session_user_id,`modifyTimestamp`='$modifyTimestamp'
	WHERE `ID`=$cmID";
	$result3 = mysql_query($sql3);
	if($result3){
	} else {
		echo "ERROR: edit comment";
	}
}


?>


<div id="comment-box" class="fmbox">
	<div class="comments">
		<div class="comment">
			<form name="add-comment" method="post" action="" >
				<textarea class="comment-textarea" name="comment" placeholder="Share your comment"></textarea>
				<input type="hidden" name="fixtureID" value="<?php echo $fixtureID ?>">
				<input type="hidden" name="sID" value="<?php echo $sbType ?>">
				<div>
				<input type="submit" name="add_comment" value="Add">
				</div>
			</form>
		</div>
	</div>

<?php 

	$result = cm_table($cm);
	echo '<div class="comments">';
	while($row = mysql_fetch_assoc($result)){
		$cmID = $row['cmID'];
		$comment = nl2br($row['cm']);
		$uID = $row['uID'];
		$uname = $row['uname'];
		$dt = nice_date($row['dt'],"d M h:m a");
		$cmrlink = '<a href="' . URL_replace_long($url,"&cmr=","&cmr=$cmID")  . '">reply</a>';
		$cmelink = '<a href="' . URL_replace_long($url,"&cme=","&cme=$cmID")  . '">edit</a>';
		
		echo '<div class="comment">';

			echo '<div class="comment-content">';
				if(isset($_GET['cme']) && !empty($_GET['cme']) && $cmID==$_GET['cme']) {
					include 'comments_edit.php';
				} else {
					echo '<div class="comment-header">';
						echo '<span class="uname">'.$uname.'</span>';
						echo '<span class="dt">'.$dt.'</span>';
					echo '</div>';
					
					echo '<div class="comment-body">';
						echo '<p>'.$comment.'</p>';
					echo '</div>';
					
					echo '<div class="comment-footer">';
						echo ($uID != $session_user_id) ? '<span id="reply" class="links">'.$cmrlink.'</span>': '<span id="reply" class="links">reply</span>';
						echo ($uID == $session_user_id) ? '<span id="edit" class="links">'.$cmelink.'</span>' : '<span id="reply" class="links">edit</span>';
					echo '</div>';				
				}
			echo '</div>';
			
			include 'comments_reply.php';
			
		echo "</div>";
	}
	echo "</div>";

?>
</div>