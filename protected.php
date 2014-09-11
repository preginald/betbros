<?php
include 'core/init.php';
include 'includes/overall/header.php';

// $url = "https://public-api.wordpress.com/rest/v1/sites/72972448/posts/72";
// $response = json_decode(file_get_contents($url));
// //print_r($response);
// $title = $response->title;
// $content = $response->content;
?>

<div class="jumbotron">
  <h1></h1>
  <p class="content"></p>
  <p><a class="btn btn-primary btn-lg" role="button">Learn more</a></p>
</div>

<!-- <h1>Sorry, you need to be logged in to do that!</h1>
<p>Please register or log in</p>
 -->
<?php include 'includes/overall/footer.php'; ?>

<script>
$(function(){
	$.ajax({
		url: "https://public-api.wordpress.com/rest/v1/sites/72972448/posts/72",
		success: function(data){
			console.log(data);
			$("h1").fadeIn().html(data.title);
			$("p.content").html(data.content);
		}
	});
});
</script>