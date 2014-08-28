$(function(){
	//var where = $('#btdash').attr('data-where');
	//btDashRefresh_v2(where);
	//btLoad(where);

	//load_labels();

	load_selection_form();
	//load_selections();
});

function load_labels () {
	$.ajax({
		url: "simulator-proc.php",
		//dataType: "json",
		data: {
			option: "getLabels"
		},
		success: function(data){

			$('div#labels').html(data);
		
		} 
	});
}

function load_selection_form () {
	$.ajax({
		url: "selection_simulator_form.php",
		success: function(data) {
			$('div#selection-form').html(data);
		}
	});
}

function load_selections (argument) {
	$.ajax({
		url: "simulator-proc.php",
		data: {
			option: "getSelections"
		},
		success: function(data) {
			$('div.table-responsive>table#selections>tbody').html(data);
		}
	});
}

$('body').on('click','div#labels.btn-group>button',function(){
	var $that 	= $(this),
		lID 	= $that.attr('id');
});

$('body').on('click','button.exec',function(e) {
	var lID = $('#label').val(),
		bank = $('#bank').val(),
		stake = $('#stake').val(),
		staking = $('#staking').val();
		
		staking = staking.trim();

    switch (staking) {
        case "1":
            var param1, param2, param3;
            break;
        case "2":
            var param1 = $('#fxd-param1').val(),
            	param2, param3;
            break;
        case "3":
			var param1 = $('#wlp-param1').val(),
				param2 = $('#wlp-param2').val(),
				param3 = $('input[name=wlp-param3]:checked').val();
            break;
        case "4":
			var param1 = $('#rst-param1').val(),
				param2 = $('#rst-param2').val(),
				param3;
            break;
    }

	$.ajax({
		url: "simulator-proc.php",
		dataType: "json",
		data: {
			option: "getSelections",
			lID: lID,
			staking: staking,
			bank: bank,
			stake: stake,
			param1: param1,
			param2: param2,
			param3: param3
		},
		success: function(data) {
			$('div#dash').removeClass('hidden');
			$('tbody#selections').html(data.html);

			$('span#tstake').html(data.tstake);
			$('span#gReturns').html(data.greturns);
			$('span#gPL').html(data.gPL);
			$('span#avgPL').html(data.avgPL);

			$('span#gPLm').html(data.gPLm);
			$('span#sRate').html(data.sRate);
			$('span#avgOdds').html(data.avgOdds);
			$('span#minOdds').html(data.minOdds);


			$('span#tBets').html(data.tBets);
			$('span#tBetsL').html(data.tBetsL);
			$('span#tBetsW').html(data.tBetsW);
			$('span#tBetsPn').html(data.tBetsPn);	
			$('span#tBetsPu').html(data.tBetsPu);			
		}
	});

	e.preventDefault;


});