<?php 

$cleantext = strip_tags($comment);

?>



<div class="comments">
	<div class="comment">
		<form name="add-comment" method="post" action="<?php echo str_replace("&cme=$cmID", "", $url); ?>" >
			<textarea class="comment-textarea" name="comment" placeholder="Share your comment"><?php echo $cleantext ?></textarea>
			<input type="hidden" name="cme" value="<?php echo $_GET['cme'] ?>">
			<div>
			<input type="submit" name="edit_comment" value="Save">
			</div>
		</form>
	</div>
</div>
