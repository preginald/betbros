$(function() {
	var username = $('#profile').data('username');
	loadProfile(username);
	loadLabels();
});


$('body').on('click','#addlabelbtn',function(){
	$('#labelsForm').slideToggle();
});

$('body').on('change','#stakingID',function(){

	var stakingID = $(this).val();
	console.log(stakingID);
	if (stakingID==2) {
		$('.fxd').show();
		$('.wlp').hide();
	} else if (stakingID==3) {
		$('.fxd').hide();
		$('.wlp').show();
	} else if (stakingID==1){
		$('.fxd').hide();
		$('.wlp').hide();
	};
});

$('body').on('click','.btn.btn-default.addlabel',function(){

	$.ajax({
		url: "user_labels_proc.php",
		data: {
			option: 'add_label',
			name: $('#name').val(),
			desc: $('#desc').val(),
			stakingID: $('#stakingID').val(),
			bank: $('#bank').val(),
			max: $('#max').val(),
			fxdParam1: 	$('#fxd-param1').val(),
			wlpParam1: 	$('#wlp-param1').val(),
			wlpParam2: 	$('#wlp-param2').val()
		},
		success: function(data){
			console.log(data);
			if (data == "SUCCESS") {
				$('#labelsForm').slideToggle();
				loadLabels();
			};
		}
	})

});

$( "body" ).on( "submit","form#profile", function( event ) {
	
	var messages = $('.alert'),
		userid 		= $('#user-id').val(),
		firstname 	= $('#firstname').val(),
		lastname 	= $('#lastname').val(),
		email 		= $('#email').val(),
		tz 			= $('#tz').val(); 

	$.ajax({
		url: "user_profile_proc.php",
		dataType: 'json',
		data: {
			option: "update",
			userid: userid,
			firstname: firstname,
			lastname: lastname,
			email: email,
			tz: tz
		},
		success: function(data){

			if (data.status == "success") {
				messages.addClass('alert-success');
				messages.html("Profile updated successfully");
			} else if (data.status == "error") {
				messages.addClass('alert-danger');
				messages.html("Error profile update "+ data.s);				
			};
		}
	});

	event.preventDefault();


});

function loadLabels(){

	$.ajax({
		url: "user_labels.php",

		success: function(data){
			//console.log(data);
			$('#labels').html(data);
		}
	});	

}

function loadProfile(username){

	$.ajax({
		url: "user_profile.php",
		data: {
			username: username
		},
		success: function(data){
			$('#profile').html(data);
		}
	});
}