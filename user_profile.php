<?php include 'core/init.php'; 

$username 		= $_GET['username'];
$user_id		= user_id_from_username($username);
$profile_data	= user_data($user_id, 'first_name', 'last_name', 'email','zone_id');

?>

<h1><?php echo $profile_data['first_name']; ?>'s Profile</h1>

<form role="form" id="profile">
	<div class="form-group">
		<label for="firstname">First Name</label>
		<input type="text" class="form-control" name="firstname" id="firstname" value="<?= $profile_data['first_name']?>" placeholder="Enter first name" required>
	</div>
	<div class="form-group">
		<label for="lastname">Last Name</label>
		<input type="text" class="form-control" name="lastname" id="lastname" value="<?= $profile_data['last_name']?>" placeholder="Enter last name" required>
	</div>
	<div class="form-group">
		<label for="email">Email</label>
		<input type="email" class="form-control" name="email" id="email" value="<?= $profile_data['email']?>"placeholder="Enter email" required>
	</div>
	<div class="form-group">
		<label for="tz">Timezone</label>
		<select class="form-control" name="tz" id="tz" required>
			<?php timezone_dropdown($profile_data['zone_id']) ?>
		</select>
	</div>

	<input type="hidden" id="user-id" value="<?= $user_id ?>" >
  <button type="submit" class="btn btn-default">Update</button>
</form>