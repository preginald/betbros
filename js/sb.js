$(function(){

	$('#bet_btns').hide();

	load_sb_2();

	$('#sb_2').delegate('.fxmkbtn','click', function(){
	  var fixtureID = $(this).attr('data-fixID');
	  var getURL = "fixture_markets.php?fixtureID="+fixtureID;

	  $.get( getURL, function( data ) {
	    $( "#markets_"+fixtureID ).html( data );
	  });
	});

	$('#sb_2').delegate('.selbtn','click', function(){
      var fixID = $(this).attr('data-fixID');
      var getURL = "fixture_selections.php?fixID="+fixID;
      $.get( getURL, function( data ) {
        $( "#sel_"+fixID ).html( data );
      });
    });

	$('#sb_2').delegate('.anabtn','click', function(){
		var sbID = $(this).attr('data-sbid');
		var getURL = "sb_02_a.php?sbID="+sbID;

		$.get( getURL, function( data ) {
			$('#sb_analysis').prepend(data);
		});
		
		// displays the bet button if analysis exists
		var liCount = $('#sb_analysis > li').length;
		console.log(liCount);
		if ( liCount >= 0 ) {
			$('#bet_btns').fadeIn();
		};



	});

	$('body').on('click','.seldelbtn', function(){
		var $that = $(this); 
		var sbID = $that.attr('data-sbid');

		$.ajax({
			url: "sb_02_del.php",
			data: { sbID: sbID},
			success: function(data){
				data = $.trim(data);
				console.log(data);
				if (data == "SUCCESS") {

					$that.closest('.sb-item').slideUp(250,function(){ $(this).remove() });
				};
			}
		});
	});

	$('#sb_analysis').on('keyup change','#odds, #stake',function(){
		var odds = $('#odds').val();
		var stake = $('#stake').val();
		var returnval = odds * stake;

		$('#return').val(returnval);		
	});

	$('body').on('click','.rmanalbtn',function(){
		var sbID = $(this).data('sbid');
		$('#sb_a_'+sbID).remove();

		// displays the bet button if analysis exists
		var liCount = $('#sb_analysis > li').length;
		console.log(liCount);
		if ( liCount == 0 ) {
			$('#bet_btns').fadeOut();
		};


	});

	$('body').on('click','#addbet', add_bet);

});


$('body').on('change','.labelID',function(){
	var $that = $(this);
	var sbID = $that.closest('.sb-item').attr('id');
	var labelID = $that.val();

	if ( labelID != '' ) {
		$.ajax({
			url: "sb_02_update_labelID.php",
			data: {
				labelID: labelID,
				sbID: sbID
			},
			success: function(data){
				console.log(data);
			}
		});
	} else {
		console.log('Select valid labelID');
	};
});



function load_sb_2(){
	$.get ( "sb_02.php" , function(data){
      $("#sb_2").html(data);
    });
}


function add_bet(){
	var bookieID = $('#bookieID').val();
	var sbID02 = $('#sbID02').val();
	var odds = $('#odds').val();
	var stake = $('#stake').val();
	
	var labelID = $('#labelID').val();
	var fixID = $('#fixID').val();
	var selID = $('#selID').val();
	var mID = $('#mID').val();	

	$.post('abc.php',{

		'bookieID[]': bookieID,
		'sbID02[]'	: sbID02,
		'odds[]'	: odds,
		'stake[]'	: stake,
		'labelID[]'	: labelID,
		'fixID[]'	: fixID,
		'selID[]'	: selID,
		'mID[]'		: mID,
		'add-bet'	: '',
		'bet-type' 	: 'Single'

	},function(data){
		console.log(data);
	});	
}