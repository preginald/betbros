
<h2>Add Bookie</h2>
<form name="add-bk" method="post" action="">
    <div class="clear">
        <select name="bkID" id="bkID" required ><?php bk_dropdown() ?></select>
        <br/>
        <label for="uname" class="tb mr">Username</label>
        <input class="w100" type="text" name="uname" id="uname" required>
        <label for="password" class="tb mr">Password</label>
        <input class="w100" type="text" name="password" id="password" >
        <input type="hidden" name="createUserID" value="<?= $user_id ?>">
	<input type="submit" name="add_user_bk" value="Add">
    </div>
</form>
