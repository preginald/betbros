$(function(){
	var esID = $('#teams_list').data('esid');
	load_teams_list(esID);
});

function load_teams_list(esID){
	$.get ( "teams-list.php?esid="+esID , function(data){
      $("#teams_list").html(data);
    });
}

$('body').on('click','.dedupbtn',function(){
	$that = $(this);
	tsID = $that.data('tsid');
	$.ajax({
		url: "teams_del_dup.php",
		data: {tsID: tsID},
		success: function(data){
			console.log(data);
			var esID = $('#teams_list').data('esid');
			load_teams_list(esID);
		}
	});
});