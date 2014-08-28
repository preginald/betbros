<?php

include 'core/init.php';

include 'includes/overall/header.php';

?>

<h1>Contact</h1>



	<form action="contact-post.php" method="post" class="ajax">

		<div>

			<input type="text" name="name" placeholder="Your name">

		</div>

		<div>

			<input type="text" name="email" placeholder="Your email">

		</div>

		<div>

			<textarea name="message" placeholder="Your message"></textarea>

		</div>



			<input type="submit" value="Send"></form>



	



<?php include 'includes/overall/footer.php'; ?>