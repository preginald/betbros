<?php 

$url = "https://public-api.wordpress.com/rest/v1/sites/72972448/posts/72";
$response = json_decode(file_get_contents($url));
//print_r($response);
$title = $response->title;
$content = $response->content;
?>

<div class="jumbotron">
  <h1><?= $title ?></h1>
  <p><?= $content ?></p>
  <p><a class="btn btn-primary btn-lg" role="button">Learn more</a></p>
</div>

<script>
$(function(){
	alert('ready');
});
</script>