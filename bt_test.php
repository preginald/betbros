<?php include 'core/init.php'; ?>
<?php require_once('includes/head.php'); ?>

<h1>Bet Tracker Test</h1>

<ul id="bt">

</ul>

<script>
$(function(){

	var url="bt_test_get.php";
	$.getJSON(url,function(json){
	// loop through the members here
	$.each(json.bt,function(i,dat){
	$("#bt").append(
	
	'<li>' + dat.btID + '</li>'
	
	);
	
	});
	
	});


});
	
</script>
