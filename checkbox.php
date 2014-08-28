<pre>
<?php print_r($_POST) ?>
</pre>
<form name="form1" method="post" action="<?=$PHP_SELF?>" >
<input type="checkbox" name="j" value="j">Jockey
<input type="checkbox" name="ht" value="ht">Horse Trainer
<input type="checkbox" name="ho" value="ho">Horse Owner
<input type="submit" name="add_person" value="Add">
</form>