<div id="topstatus" class="">
	<div class="r w300 p5">
	<?php
	if (logged_in() === true) {
		?>
		<a href="<?php echo "index.php?page=profile" . "&username=" . $user_data['username']; ?>"><?= $user_data['first_name']; ?></a>
		<a href="logout.php">Log out</a>
		<?php
	} else {
		?>
		<form action="login.php" method="post">

					<input class="l w100 mr" type="text" name="username">

					<input class="l w100 mr" type="password" name="password">

					<input class="l w50 mr" type="submit" value="Log in">

		</form>
		<?php
	}
	?>
	</div>
</div>