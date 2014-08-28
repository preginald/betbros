<?php 
	$result_r = cmr_table($cmID);
	echo '<div class="comments">';
	while($row_r = mysql_fetch_assoc($result_r)){
		$cmID = $row_r['cmID'];
		$replyID = $row_r['replyID'];
		$comment = htmlspecialchars(urldecode($row_r['cm']));
		$ruID = $row_r['uID'];
		$uname = $row_r['uname'];
		$dt = nice_date($row_r['dt'],"d M h:m a");
		$cmrlink = '<a href="' . URL_replace_long($url,"&cmr=","&cmr=$cmID")  . '">reply</a>';
		$cmelink = '<a href="' . URL_replace_long($url,"&cme=","&cme=$cmID")  . '">edit</a>';
		
		echo '<div class="cmr">';

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
						echo ($ruID != $session_user_id) ? '<span id="reply" class="links">'.$cmrlink.'</span>': '<span id="reply" class="links">reply</span>';
						echo ($ruID == $session_user_id) ? '<span id="edit" class="links">'.$cmelink.'</span>' : '<span id="reply" class="links">edit</span>';
					echo '</div>';				
				}
			echo '</div>';
			
			//
			//include 'comments_reply.php';
			//
			
		echo "</div>";
	}
	echo "</div>";

?>

<?php if(isset($_GET['cmr']) && !empty($_GET['cmr']) && $cmID==$_GET['cmr']) {?>
	<div class="cmr">
		<div class="comment">
			<form name="add-comment" method="post" action="<?php echo str_replace("&cmr=$cmID", "", $url); ?>" >
				<textarea class="comment-textarea" name="comment" placeholder="Share your comment"></textarea>
				<input type="hidden" name="cmr" value="<?php echo $_GET['cmr'] ?>">
				<input type="hidden" name="fixtureID" value="<?php echo $fixtureID ?>">
				<input type="hidden" name="sID" value="<?php echo $sbType ?>">
				<div>
				<input type="submit" name="reply_comment" value="Reply">
				</div>
			</form>
		</div>
	</div>
<?php }?>