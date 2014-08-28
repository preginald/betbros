$(function(){
	var where = $('#btdash').attr('data-where');
	load_bt_form();
	//btDashRefresh_v2(where);
	//btLoad(where);
});



function btLoad(where){
	$.post('btLoad.php',{
		where: where
	}, 
	function(data){
		$('#bt').html(data);
	});
}

function load_bt_form () {
	$.ajax({
		url: "bt_form.php",
		success: function(data) {
			$('div#bt-form').html(data);
		}
	});
}

function bt_delete(btID){
	var $that = $(this);

	$.post('bet-tracker-delete.php',
	{
		task: 'delete',
		btID: btID
	})
	.success(function(data){
		$('#'+ btID).detach();
		//$that.closest('.bt-item').slideUp(250,function(){ $(this).remove() });
	})

	btDashRefresh_v2($('#btdash').attr('data-where'));


}

function btDashRefresh_v2(where){

	//console.log(where);

	$.post('bet-tracker-dashboard.php',	{where: where}, function(data){
		$('#btdash').html(data);
	})
	
}

function btDashRefresh(){

	$.ajaxSetup({

		cache: false,
		beforeSend: function(){
			$('#btdash').show();
		},
		complete: function(){
			$('#btdash').show();
		},
		success: function(){
			$('#btdash').show();
		}
	});

	var container = $('#btdash');
	container.load('bet-tracker-dashboard.php');
	var refreshID = setInterval(function(){
		container.load('bet-tracker-dashboard.php');
	},1000);	

}


//delete btID and related bdID
$('body').on('click','.btdelbtn',function(){
	var $that = $(this);
	var btID = $that.closest('.bt-item').attr('id');
	//console.log(btID);
	if(confirm("Are you sure you want to delete bet ID "+btID)){
		$.post('bet-tracker-delete.php',
		{
			task: 'delete',
			btID: btID
		})
		.success(function(data){
			//$('#'+ btID).detach();
			$that.closest('.bt-item').slideUp(250,function(){ $(this).remove() });
		})

		btDashRefresh_v2($('#btdash').attr('data-where'));
		btLoad($('#btdash').attr('data-where'));

	}

	return false

});

$('body').on('click','h1',function(){
	//console.log('clicked');
	var arr = '';
	
	$('.btn.btn-primary.active > input').each(function(index){
		arr += $(this).attr('data-lid');
		arr += ",";
	});
	console.log(arr);

});

$('body').on('change','.labelID',function(){
	var $that = $(this);
	var btID = $that.closest('.bt-item').attr('id');
	var labelID = $that.val();

	if ( labelID != '' ) {
		$.ajax({
			url: "bt_update_labelID.php",
			data: {
				labelID: labelID,
				btID: btID
			},
			success: function(data){
				console.log(data);
			}
		});
	} else {
		console.log('Select valid labelID');
	};
});

$('body').on('click','button.exec',function(e) {
	var lID = $('#label').val();

	$.ajax({
		url: "btLoad.php",
		//dataType: "json",
		data: {
			option: "filterBT",
			lID: lID
		},
		success: function(data) {
			$('div#dash').removeClass('hidden');
			$('ul#bt').html(data);		
		}
	});

	$.ajax({
		url: "bet-tracker-dashboard.php",
		//dataType: "json",
		data: {
			option: "filterBT",
			lID: lID
		},
		success: function(data) {
			$('#btdash').html(data);		
		}
	});

	e.preventDefault;


});